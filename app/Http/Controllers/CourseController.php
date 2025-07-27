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
     * Store course with improved video URL handling
     */
    public function store(Request $request)
    {
        // Dynamic validation based on content type and video option
        $rules = [
            'title' => 'required|string|max:255',
            'thumbnail' => 'nullable|image|max:2048',
            'content_type' => 'required|in:article,video,audio,pdf',
        ];

        // Add conditional validation rules
        switch ($request->content_type) {
            case 'article':
                $rules['description'] = 'required|string|max:10000';
                break;
                
            case 'video':
                $rules['video_option'] = 'required|in:upload,url';
                
                if ($request->video_option === 'upload') {
                    $rules['video_file'] = 'required|file|mimetypes:video/mp4,video/avi,video/mov,video/wmv,video/flv,video/webm|max:4096'; // 4MB for Vercel
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

        $request->validate($rules);

        // Additional file size check for video uploads
        if ($request->content_type === 'video' && $request->video_option === 'upload') {
            if ($request->hasFile('video_file')) {
                $videoSize = $request->file('video_file')->getSize();
                if ($videoSize > 4 * 1024 * 1024) { // 4MB limit for Vercel
                    return back()->withErrors(['video_file' => 'Video file too large for direct upload. Please use video URL option or reduce file size.']);
                }
            }
        }

        try {
            // Upload thumbnail to Supabase (optional)
            $thumbnailPath = '-';
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = SupabaseUploader::upload($request->file('thumbnail'), 'thumbnail');
            }

            // Create Course
            $course = Course::create([
                'title' => $request->title,
                'description' => $request->description ?? '-',
                'thumbnail' => $thumbnailPath,
                'status' => 'pending',
                'user_id' => auth()->id(),
            ]);

            // Handle Course Content
            $contentData = $this->handleContentUpload($request);

            // Save content if exists
            if ($contentData) {
                CourseContent::create([
                    'course_id' => $course->id,
                    'content_type' => $request->content_type,
                    'content' => $contentData['content'],
                    'content_metadata' => $contentData['metadata'] ?? null,
                    'order' => 1,
                ]);

                // Create video link reference if it's a URL
                if ($request->content_type === 'video' && $request->video_option === 'url') {
                    $this->createVideoLinkReference($request->video_url, $course->title, $course->id);
                }
            }

            return redirect()->route('home')->with('success', 'Course submitted for review successfully!');
            
        } catch (\Exception $e) {
            Log::error('Course creation failed: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'request_data' => $request->except(['_token', 'video_file', 'audio_file', 'pdf_file', 'thumbnail'])
            ]);
            
            return back()->withInput()->withErrors(['error' => 'Failed to create course. Please try again.']);
        }
    }

    /**
     * Store course with pre-uploaded file paths (for large files)
     */
    public function storeWithPaths(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:10000',
            'content_type' => 'required|in:article,video,audio,pdf',
            'thumbnail' => 'nullable|string|max:500', // Path dari direct upload
        ];

        // Add conditional validation for pre-uploaded paths
        switch ($request->content_type) {
            case 'video':
                $rules['video_option'] = 'nullable|in:upload,url';
                $rules['video_url'] = 'nullable|url|max:500';
                $rules['video_file'] = 'nullable|string|max:500'; // Path dari direct upload
                break;
            case 'audio':
                $rules['audio_file'] = 'nullable|string|max:500';
                break;
            case 'pdf':
                $rules['pdf_file'] = 'nullable|string|max:500';
                break;
        }

        $request->validate($rules);

        try {
            // Create Course
            $course = Course::create([
                'title' => $request->title,
                'description' => $request->description ?? '-',
                'thumbnail' => $request->thumbnail ?? '-',
                'status' => 'pending',
                'user_id' => auth()->id(),
            ]);

            // Handle Course Content with paths
            $contentData = $this->handleContentWithPaths($request);

            // Save content if exists
            if ($contentData) {
                CourseContent::create([
                    'course_id' => $course->id,
                    'content_type' => $request->content_type,
                    'content' => $contentData['content'],
                    'content_metadata' => $contentData['metadata'] ?? null,
                    'order' => 1,
                ]);

                // Create video link reference if it's a URL
                if ($request->content_type === 'video' && $request->video_option === 'url') {
                    $this->createVideoLinkReference($request->video_url, $course->title, $course->id);
                }
            }

            return response()->json([
                'success' => true, 
                'message' => 'Course created successfully',
                'course_id' => $course->id
            ]);
            
        } catch (\Exception $e) {
            Log::error('Course creation with paths failed: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'request_data' => $request->all()
            ]);
            
            return response()->json(['success' => false, 'message' => 'Failed to create course'], 500);
        }
    }

    /**
     * Handle content upload with file paths (for direct upload)
     */
    private function handleContentWithPaths(Request $request)
    {
        switch ($request->content_type) {
            case 'article':
                return ['content' => $request->description];

            case 'video':
                if ($request->video_option === 'upload' && $request->video_file) {
                    return [
                        'content' => $request->video_file,
                        'metadata' => json_encode([
                            'type' => 'upload',
                            'source' => 'direct_upload'
                        ])
                    ];
                } elseif ($request->video_option === 'url' && $request->video_url) {
                    $urlInfo = $this->getVideoUrlInfo($request->video_url);
                    return [
                        'content' => $request->video_url,
                        'metadata' => json_encode(array_merge($urlInfo, ['type' => 'url']))
                    ];
                }
                break;

            case 'audio':
                if ($request->audio_file) {
                    return [
                        'content' => $request->audio_file,
                        'metadata' => json_encode(['type' => 'upload'])
                    ];
                }
                break;

            case 'pdf':
                if ($request->pdf_file) {
                    return [
                        'content' => $request->pdf_file,
                        'metadata' => json_encode(['type' => 'upload'])
                    ];
                }
                break;
        }

        return null;
    }

    /**
     * Handle content upload with actual files (traditional way)
     */
    private function handleContentUpload(Request $request)
    {
        switch ($request->content_type) {
            case 'article':
                return ['content' => $request->description];

            case 'video':
                if ($request->video_option === 'upload' && $request->hasFile('video_file')) {
                    $uploadedPath = SupabaseUploader::upload($request->file('video_file'), 'video');
                    return [
                        'content' => $uploadedPath,
                        'metadata' => json_encode([
                            'type' => 'upload',
                            'original_name' => $request->file('video_file')->getClientOriginalName(),
                            'size' => $request->file('video_file')->getSize()
                        ])
                    ];
                } elseif ($request->video_option === 'url' && $request->video_url) {
                    $urlInfo = $this->getVideoUrlInfo($request->video_url);
                    return [
                        'content' => $request->video_url,
                        'metadata' => json_encode(array_merge($urlInfo, ['type' => 'url']))
                    ];
                }
                break;

            case 'audio':
                if ($request->hasFile('audio_file')) {
                    $uploadedPath = SupabaseUploader::upload($request->file('audio_file'), 'audio');
                    return [
                        'content' => $uploadedPath,
                        'metadata' => json_encode([
                            'type' => 'upload',
                            'original_name' => $request->file('audio_file')->getClientOriginalName(),
                            'size' => $request->file('audio_file')->getSize()
                        ])
                    ];
                }
                break;

            case 'pdf':
                if ($request->hasFile('pdf_file')) {
                    $uploadedPath = SupabaseUploader::upload($request->file('pdf_file'), 'pdf');
                    return [
                        'content' => $uploadedPath,
                        'metadata' => json_encode([
                            'type' => 'upload',
                            'original_name' => $request->file('pdf_file')->getClientOriginalName(),
                            'size' => $request->file('pdf_file')->getSize()
                        ])
                    ];
                }
                break;
        }

        return null;
    }

    /**
     * Extract video URL information
     */
    private function getVideoUrlInfo($url)
    {
        $info = [
            'original_url' => $url,
            'platform' => 'unknown',
            'video_id' => null,
            'processed_at' => now()->toDateTimeString()
        ];

        // Clean URL
        $url = trim($url);

        // YouTube validation
        if (strpos($url, 'youtube.com/watch') !== false || strpos($url, 'youtu.be/') !== false) {
            $info['platform'] = 'youtube';
            
            if (strpos($url, 'youtube.com/watch') !== false) {
                parse_str(parse_url($url, PHP_URL_QUERY), $query);
                $info['video_id'] = $query['v'] ?? null;
            } else {
                // youtu.be format
                $info['video_id'] = substr(parse_url($url, PHP_URL_PATH), 1);
            }
            
        } elseif (strpos($url, 'vimeo.com/') !== false) {
            $info['platform'] = 'vimeo';
            $path = parse_url($url, PHP_URL_PATH);
            $info['video_id'] = trim($path, '/');
            
        } elseif (strpos($url, 'dailymotion.com') !== false) {
            $info['platform'] = 'dailymotion';
            
        } elseif (strpos($url, 'twitch.tv') !== false) {
            $info['platform'] = 'twitch';
            
        } elseif (preg_match('/\.(mp4|webm|ogg|avi|mov|wmv|flv|mkv)(\?.*)?$/i', $url)) {
            $info['platform'] = 'direct_link';
        }

        return $info;
    }

    /**
     * Create a reference file for video links in Supabase or local storage
     */
    private function createVideoLinkReference($url, $title, $courseId)
    {
        try {
            $linkData = [
                'course_id' => $courseId,
                'title' => $title,
                'url' => $url,
                'url_info' => $this->getVideoUrlInfo($url),
                'created_at' => now()->toDateTimeString(),
                'type' => 'video_link'
            ];

            $filename = 'video_link_' . $courseId . '_' . time() . '.json';
            $content = json_encode($linkData, JSON_PRETTY_PRINT);
            
            // Try to store in Supabase first, fallback to local
            try {
                // If you have a method to store JSON files in Supabase
                $path = SupabaseUploader::uploadText($content, 'video/links/' . $filename);
                Log::info('Video link reference created in Supabase', ['path' => $path]);
            } catch (\Exception $e) {
                // Fallback to local storage
                Storage::disk('public')->put('edufiles/video/link/' . $filename, $content);
                Log::info('Video link reference created locally', ['filename' => $filename]);
            }
            
        } catch (\Exception $e) {
            Log::warning('Failed to create video link reference: ' . $e->getMessage(), [
                'course_id' => $courseId,
                'url' => $url
            ]);
        }
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
            $user->enrolledCourses()->attach($course->id, ['enrolled_at' => now()]);
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