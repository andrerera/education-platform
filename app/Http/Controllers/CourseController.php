<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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

        $isEnrolled = auth()->check()
            ? $course->students()->where('user_id', auth()->id())->exists()
            : false;

        return view('courses.show', compact('course', 'contents', 'isEnrolled'));
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
        'video_file' => 'nullable|file|mimetypes:video/*',
        'video_url' => 'nullable|url',
        'audio_file' => 'nullable|file|mimes:mp3',
        'pdf_file' => 'nullable|file|mimes:pdf',
    ]);

    // ✅ Upload thumbnail ke Supabase (jika ada)
    $thumbnailPath = null;
    if ($request->hasFile('thumbnail')) {
        $thumbnailPath = SupabaseUploader::upload($request->file('thumbnail'), 'thumbnail');
    }

    // 📝 Simpan course
    $course = Course::create([
        'title' => $request->title,
        'description' => $request->description ?? '-',
        'thumbnail' => $thumbnailPath,
        'status' => 'pending',
        'user_id' => auth()->id(),
    ]);

    // 🔀 Upload konten utama
    $contentPath = null;
    switch ($request->content_type) {
        case 'article':
            $contentPath = $request->description;
            break;

        case 'video':
            if ($request->video_option === 'upload' && $request->hasFile('video_file')) {
                $contentPath = SupabaseUploader::upload($request->file('video_file'), 'video/uploaded');
            } elseif ($request->video_option === 'url') {
                $contentPath = $request->video_url;
            }
            break;

        case 'audio':
            if ($request->hasFile('audio_file')) {
                $contentPath = SupabaseUploader::upload($request->file('audio_file'), 'audio');
            }
            break;

        case 'pdf':
            if ($request->hasFile('pdf_file')) {
                $contentPath = SupabaseUploader::upload($request->file('pdf_file'), 'pdf');
            }
            break;
    }

    // 💾 Simpan ke tabel CourseContent
    if ($contentPath) {
        CourseContent::create([
            'course_id' => $course->id,
            'content_type' => $request->content_type,
            'content' => $contentPath,
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