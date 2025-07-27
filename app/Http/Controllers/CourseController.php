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

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'thumbnail' => 'nullable|image',
            'content_type' => 'required|in:article,video,audio,pdf',
            'description' => 'nullable|string',
            'video_option' => 'nullable|in:upload,url',
            'video_file' => 'nullable|file|mimetypes:video/*|max:51200',
            'video_url' => 'nullable|url',
            'audio_file' => 'nullable|file|mimes:mp3',
            'pdf_file' => 'nullable|file|mimes:pdf',
        ]);

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
        $content = null;

        switch ($request->content_type) {
            case 'article':
                $content = $request->description;
                break;

            case 'video':
                if ($request->video_option === 'upload' && $request->hasFile('video_file')) {
                    $content = SupabaseUploader::upload($request->file('video_file'), 'video/uploaded');
                } elseif ($request->video_option === 'url') {
                    $content = $request->video_url;
                }
                break;

            case 'audio':
                if ($request->hasFile('audio_file')) {
                    $content = SupabaseUploader::upload($request->file('audio_file'), 'audio');
                }
                break;

            case 'pdf':
                if ($request->hasFile('pdf_file')) {
                    $content = SupabaseUploader::upload($request->file('pdf_file'), 'pdf');
                }
                break;
        }

        // Save content if exists
        if ($content) {
            CourseContent::create([
                'course_id' => $course->id,
                'content_type' => $request->content_type,
                'content' => $content,
            ]);
        }

        return redirect()->route('home')->with('success', 'Course submitted for review.');
    }

    public function mySubmissions()
    {
        $courses = Course::with('contents')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('courses.my_submissions', compact('courses'));
    }

    public function enroll(Request $request, Course $course)
    {
        $user = auth()->user();

        if (!$user->enrolledCourses()->where('course_id', $course->id)->exists()) {
            $user->enrolledCourses()->attach($course->id);
        }

        return redirect()->route('courses.show', $course)->with('success', 'Berhasil mendaftar ke kursus!');
    }
}