<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\SupabaseUploader;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::where('status', 'approved')
            ->withCount(['students'])
            ->latest()
            ->get();

        return view('courses.index', compact('courses'));
    }

    public function show(Course $course)
    {
        $contents = $course->contents()->orderBy('order')->get();

        $isEnrolled = false;
        if (auth()->check()) {
            $isEnrolled = $course->students()->where('user_id', auth()->id())->exists();
        }

        return view('courses.show', [
            'course' => $course,
            'contents' => $contents,
            'isEnrolled' => $isEnrolled,
        ]);
    }

    public function create()
    {
        return view('courses.create');
    }
    /**
     * Sanitize string to ensure valid UTF-8
     */
    private function sanitizeUtf8($data)
    {
        if (is_string($data)) {
            // Remove invalid UTF-8 characters and convert to UTF-8
            $data = mb_convert_encoding($data, 'UTF-8', 'auto');
            // Replace any remaining invalid characters with a placeholder
            $data = preg_replace('/[^\x{0009}\x{000A}\x{000D}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]/u', '', $data);
            return $data;
        } elseif (is_array($data)) {
            // Recursively sanitize arrays
            return array_map([$this, 'sanitizeUtf8'], $data);
        }
        return $data;
    }

    public function store(Request $request)
    {
        Log::info('Course store started', [
            'user_id' => auth()->id(),
            'input' => $request->except(['_token', 'video_file', 'audio_file', 'pdf_file', 'thumbnail']),
            'files' => array_keys($request->allFiles())
        ]);

        // Sanitize string inputs
        $request->merge([
            'title' => $this->sanitizeUtf8($request->title),
            'description' => $this->sanitizeUtf8($request->description),
            'video_url' => $this->sanitizeUtf8($request->video_url),
        ]);

        // Dynamic validation
        $rules = [
            'title' => 'required|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'content_type' => 'required|in:article,video,audio,pdf',
        ];

        switch ($request->content_type) {
            case 'article':
                $rules['description'] = 'required|string|max:10000';
                break;
            case 'video':
                $rules['video_option'] = 'required|in:upload,url';
                if ($request->video_option === 'upload') {
                    $rules['video_file'] = 'required|file|mimes:mp4,avi,mov,wmv,flv,webm|max:4096';
                } else {
                    $rules['video_url'] = 'required|url|max:500';
                }
                break;
            case 'audio':
                $rules['audio_file'] = 'required|file|mimes:mp3,wav,m4a|max:4096';
                break;
            case 'pdf':
                $rules['pdf_file'] = 'required|file|mimes:pdf|max:4096';
                break;
        }

        try {
            $validated = $request->validate($rules);
            Log::info('Validation passed', ['validated' => $validated]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            return back()->withInput()->withErrors($e->errors());
        }

        try {
            // Upload thumbnail
            $thumbnailPath = '-';
            if ($request->hasFile('thumbnail') && $request->file('thumbnail')->isValid()) {
                Log::info('Uploading thumbnail');
                $thumbnailPath = SupabaseUploader::upload($request->file('thumbnail'), 'thumbnail');
                Log::info('Thumbnail uploaded', ['path' => $thumbnailPath]);
            }

            // Create Course
            $course = Course::create([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? '-',
                'thumbnail' => $thumbnailPath,
                'status' => 'pending',
                'user_id' => auth()->id(),
            ]);
            Log::info('Course created', ['course_id' => $course->id]);

            // Handle Course Content
            $contentData = $this->handleContentUpload($request);
            Log::info('Content data processed', ['content_data' => $contentData]);

            if ($contentData) {
                CourseContent::create([
                    'course_id' => $course->id,
                    'content_type' => $request->content_type,
                    'content' => $contentData['content'],
                    'content_metadata' => $contentData['metadata'] ?? null,
                    'order' => 1,
                ]);
                Log::info('Course content created', ['course_id' => $course->id]);

                if ($request->content_type === 'video' && $request->video_option === 'url') {
                    $this->createVideoLinkReference($request->video_url, $course->title, $course->id);
                    Log::info('Video link reference created');
                }
            }

            return redirect()->route('home')->with('success', 'Submit mu sedang direview');
        } catch (\Exception $e) {
            Log::error('Course creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'request_data' => $request->except(['_token', 'video_file', 'audio_file', 'pdf_file', 'thumbnail'])
            ]);
            return back()->withInput()->with('error', 'Failed to create course: ' . $e->getMessage());
        }
    }

    private function handleContentUpload(Request $request)
    {
        switch ($request->content_type) {
            case 'article':
                return ['content' => $this->sanitizeUtf8($request->description)];

            case 'video':
                if ($request->video_option === 'upload' && $request->hasFile('video_file') && $request->file('video_file')->isValid()) {
                    $uploadedPath = SupabaseUploader::upload($request->file('video_file'), 'video');
                    return [
                        'content' => $uploadedPath,
                        'metadata' => json_encode($this->sanitizeUtf8([
                            'type' => 'upload',
                            'original_name' => $request->file('video_file')->getClientOriginalName(),
                            'size' => $request->file('video_file')->getSize()
                        ]), JSON_THROW_ON_ERROR)
                    ];
                } elseif ($request->video_option === 'url' && $request->video_url) {
                    $urlInfo = $this->sanitizeUtf8($this->getVideoUrlInfo($request->video_url));
                    return [
                        'content' => $request->video_url,
                        'metadata' => json_encode(array_merge($urlInfo, ['type' => 'url']), JSON_THROW_ON_ERROR)
                    ];
                }
                break;

            case 'audio':
                if ($request->hasFile('audio_file') && $request->file('audio_file')->isValid()) {
                    $uploadedPath = SupabaseUploader::upload($request->file('audio_file'), 'audio');
                    return [
                        'content' => $uploadedPath,
                        'metadata' => json_encode($this->sanitizeUtf8([
                            'type' => 'upload',
                            'original_name' => $request->file('audio_file')->getClientOriginalName(),
                            'size' => $request->file('audio_file')->getSize()
                        ]), JSON_THROW_ON_ERROR)
                    ];
                }
                break;

            case 'pdf':
                if ($request->hasFile('pdf_file') && $request->file('pdf_file')->isValid()) {
                    $uploadedPath = SupabaseUploader::upload($request->file('pdf_file'), 'pdf');
                    return [
                        'content' => $uploadedPath,
                        'metadata' => json_encode($this->sanitizeUtf8([
                            'type' => 'upload',
                            'original_name' => $request->file('pdf_file')->getClientOriginalName(),
                            'size' => $request->file('pdf_file')->getSize()
                        ]), JSON_THROW_ON_ERROR)
                    ];
                }
                break;
        }

        return null;
    }

    private function createVideoLinkReference($url, $title, $courseId)
    {
        try {
            $linkData = $this->sanitizeUtf8([
                'course_id' => $courseId,
                'title' => $title,
                'url' => $url,
                'url_info' => $this->getVideoUrlInfo($url),
                'created_at' => now()->toDateTimeString(),
                'type' => 'video_link'
            ]);

            $filename = 'video_link_' . $courseId . '_' . time() . '.json';
            $content = json_encode($linkData, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
            
            try {
                $path = SupabaseUploader::uploadText($content, 'video/links/' . $filename);
                Log::info('Video link reference created in Supabase', ['path' => $path]);
            } catch (\Exception $e) {
                Storage::disk('public')->put('edufiles/video/link/' . $filename, $content);
                Log::info('Video link reference created locally', ['filename' => $filename]);
            }
        } catch (\Exception $e) {
            Log::warning('Failed to create video link reference: ' . $e->getMessage(), [
                'course_id' => $courseId,
                'url' => $url
            ]);
            throw $e;
        }
    }

    private function getVideoUrlInfo($url)
    {
        $url = $this->sanitizeUtf8($url);
        $info = [
            'original_url' => $url,
            'platform' => 'unknown',
            'video_id' => null,
            'processed_at' => now()->toDateTimeString()
        ];

        if (strpos($url, 'youtube.com/watch') !== false || strpos($url, 'youtu.be/') !== false) {
            $info['platform'] = 'youtube';
            if (strpos($url, 'youtube.com/watch') !== false) {
                parse_str(parse_url($url, PHP_URL_QUERY), $query);
                $info['video_id'] = $this->sanitizeUtf8($query['v'] ?? null);
            } else {
                $info['video_id'] = $this->sanitizeUtf8(substr(parse_url($url, PHP_URL_PATH), 1));
            }
        } elseif (strpos($url, 'vimeo.com/') !== false) {
            $info['platform'] = 'vimeo';
            $path = parse_url($url, PHP_URL_PATH);
            $info['video_id'] = $this->sanitizeUtf8(trim($path, '/'));
        } elseif (strpos($url, 'dailymotion.com') !== false) {
            $info['platform'] = 'dailymotion';
        } elseif (strpos($url, 'twitch.tv') !== false) {
            $info['platform'] = 'twitch';
        } elseif (preg_match('/\.(mp4|webm|ogg|avi|mov|wmv|flv|mkv)(\?.*)?$/i', $url)) {
            $info['platform'] = 'direct_link';
        }

        return $info;
    }

    public function mySubmissions()
    {
        $courses = Course::with(['contents:id,course_id,content_type,content_metadata'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('courses.my_submissions', compact('courses'));
    }

public function enroll(Request $request, Course $course)
{
    $user = auth()->user();

    if (!$user) {
        return redirect()->route('login')->with('error', 'Please log in to enroll.');
    }

    if ($course->status !== 'approved') {
        return redirect()->back()->with('error', 'This course is not available for enrollment.');
    }

    if (!$user->enrolledCourses()->where('course_id', $course->id)->exists()) {
        $user->enrolledCourses()->attach($course->id); // Removed 'enrolled_at' => now()
        return redirect()->back()->with('success', 'Successfully enrolled in the course.');
    }

    return redirect()->back()->with('info', 'You are already enrolled in this course.');
}

    /**
     * Get file size limits for frontend reference
     */
    public function getFileSizeLimits()
    {
        return response()->json([
            'limits' => [
                'thumbnail' => '2MB',
                'video' => '4MB', // Vercel limit
                'audio' => '4MB',
                'pdf' => '4MB'
            ],
            'bytes' => [
                'thumbnail' => 2 * 1024 * 1024,
                'video' => 4 * 1024 * 1024,
                'audio' => 4 * 1024 * 1024,
                'pdf' => 4 * 1024 * 1024
            ]
        ]);
    }
}