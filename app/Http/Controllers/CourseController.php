<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\SupabaseUploader;

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
     * Store course with file uploads (traditional way - limited by Vercel)
     */
    public function store(Request $request)
    {
        // Check file sizes before processing
        if ($request->hasFile('video_file')) {
            $videoSize = $request->file('video_file')->getSize();
            if ($videoSize > 4 * 1024 * 1024) { // 4MB limit for Vercel
                return back()->withErrors(['video_file' => 'Video file too large for direct upload. Please use the alternative upload method.']);
            }
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'thumbnail' => 'nullable|image|max:2048',
            'content_type' => 'required|in:article,video,audio,pdf',
            'description' => 'nullable|string|max:5000',
            'video_option' => 'required_if:content_type,video|in:upload,url',
            'video_file' => 'nullable|file|mimetypes:video/mp4,video/avi,video/mov,video/wmv,video/flv,video/webm|max:4096', // 4MB for Vercel
            'video_url' => 'required_if:video_option,url|url',
            'audio_file' => 'required_if:content_type,audio|file|mimes:mp3,wav,m4a|max:4096',
            'pdf_file' => 'required_if:content_type,pdf|file|mimes:pdf|max:4096',
        ]);

        try {
            // Upload thumbnail to Supabase (optional)
            $thumbnailPath = null;
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = SupabaseUploader::upload($request->file('thumbnail'), 'thumbnail');
            }

            // Create Course
            $course = Course::create([
                'title' => $request->title,
                'description' => $request->description ?? '-',
                'thumbnail' => $thumbnailPath ?? '-',
                'status' => 'pending',
                'user_id' => auth()->id(),
            ]);

            // Handle Course Content
            $content = $this->handleContentUpload($request);

            // Save content if exists
            if ($content) {
                CourseContent::create([
                    'course_id' => $course->id,
                    'content_type' => $request->content_type,
                    'content' => $content,
                    'order' => 1,
                ]);
            }

            return redirect()->route('home')->with('success', 'Course submitted for review.');
            
        } catch (\Exception $e) {
            \Log::error('Course creation failed: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Failed to create course. Please try again.']);
        }
    }

    /**
     * Store course with pre-uploaded file paths (for large files)
     */
    public function storeWithPaths(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'content_type' => 'required|in:article,video,audio,pdf',
            'video_option' => 'nullable|in:upload,url',
            'video_url' => 'nullable|url',
            'thumbnail' => 'nullable|string', // Path dari direct upload
            'video_file' => 'nullable|string', // Path dari direct upload
            'audio_file' => 'nullable|string', // Path dari direct upload
            'pdf_file' => 'nullable|string', // Path dari direct upload
        ]);

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
            $content = $this->handleContentWithPaths($request);

            // Save content if exists
            if ($content) {
                CourseContent::create([
                    'course_id' => $course->id,
                    'content_type' => $request->content_type,
                    'content' => $content,
                    'order' => 1,
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Course created successfully']);
            
        } catch (\Exception $e) {
            \Log::error('Course creation failed: ' . $e->getMessage());
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
                return $request->description;

            case 'video':
                if ($request->video_option === 'upload' && $request->video_file) {
                    return $request->video_file; // Already uploaded path
                } elseif ($request->video_option === 'url') {
                    return $request->video_url;
                }
                break;

            case 'audio':
                if ($request->audio_file) {
                    return $request->audio_file; // Already uploaded path
                }
                break;

            case 'pdf':
                if ($request->pdf_file) {
                    return $request->pdf_file; // Already uploaded path
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
                return $request->description;

            case 'video':
                if ($request->video_option === 'upload' && $request->hasFile('video_file')) {
                    return SupabaseUploader::upload($request->file('video_file'), 'video');
                } elseif ($request->video_option === 'url') {
                    return $request->video_url;
                }
                break;

            case 'audio':
                if ($request->hasFile('audio_file')) {
                    return SupabaseUploader::upload($request->file('audio_file'), 'audio');
                }
                break;

            case 'pdf':
                if ($request->hasFile('pdf_file')) {
                    return SupabaseUploader::upload($request->file('pdf_file'), 'pdf');
                }
                break;
        }

        return null;
    }

    public function mySubmissions()
    {
        $courses = Course::with(['contents:id,course_id,content_type'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('courses.my_submissions', compact('courses'));
    }

    public function enroll(Request $request, Course $course)
    {
        $user = auth()->user();

        if (!$user->enrolledCourses()->where('course_id', $course->id)->exists()) {
            $user->enrolledCourses()->attach($course->id, [
                'enrolled_at' => now()
            ]);
        }

        return redirect()->route('courses.show', $course)->with('success', 'Berhasil mendaftar ke kursus!');
    }
}