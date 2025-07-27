@php use Illuminate\Support\Str; @endphp

@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto text-indigo-600 rounded-2xl p-4 overflow-hidden items-center">
    @auth
        @if($isEnrolled)
            @php
                $mainContent = $contents->first();
            @endphp

            <!-- Header & Video/PDF -->
            <div class="p-8 pb-4">
                <h1 class="text-2xl md:text-3xl font-bold mb-6">{{ $course->title }}</h1>

                <!-- Tampilkan video atau PDF -->
                @if($mainContent && $mainContent->content_type === 'video')
                    <div class="mt-6">
                        <h2 class="text-lg font-bold mb-2">Materi</h2>

                        @if(Str::startsWith($mainContent->content, 'http'))
                            <!-- Embed YouTube -->
            <div class="w-full md:w-3/4 mx-auto mt-3 mb-6 aspect-video">
                <iframe class="w-full h-full rounded-xl"
                src="https://www.youtube.com/embed/{{ Str::after($mainContent->content, 'v=') }}"
                frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe>
            </div>
                        @else
                            <!-- Play Uploaded Video -->
                            <video controls class="w-full rounded">
                                <source src="{{ asset('storage/' . $mainContent->content) }}" type="video/mp4">
                                Browser kamu tidak mendukung pemutar video.
                            </video>
                        @endif
                    </div>
                @elseif($mainContent && $mainContent->content_type === 'pdf')
                    <div class="rounded-xl overflow-hidden mb-6 bg-gray-800">
                        <iframe src="{{ Storage::url($mainContent->content) }}" class="w-full h-[400px] md:h-[600px] rounded" frameborder="0"></iframe>
                    </div>
                @endif

                <!-- Instructors -->
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 rounded-full bg-indigo-600 text-white flex items-center justify-center text-xl font-bold">
                        {{ strtoupper(substr($course->creator->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Instructor / Creator</div>
                        <div class="font-semibold text-gray-900">{{ $course->creator->name }}</div>
                        <div class="text-sm text-gray-600">{{ $course->creator->email }}</div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="bg-white px-8 pt-4 rounded-b-2xl shadow-inner">
                <div class="flex border-b border-gray-200 mb-4">
                    <button id="tab-overview"
                            class="py-2 px-4 font-semibold border-b-2 transition-colors duration-200 border-indigo-600 text-indigo-600"
                            onclick="showTab('overview')">
                        Overview
                    </button>
                    <button id="tab-comments"
                            class="py-2 px-4 font-semibold border-b-2 border-transparent text-gray-500 hover:text-indigo-600 transition"
                            onclick="showTab('comments')">
                        Comments
                    </button>
                </div>

                <!-- Overview Tab -->
                <div id="overview-tab" class="block text-gray-700">
                    <div class="mb-6 leading-relaxed">
                        <div class="mb-2">{{ $course->description }}</div>
                        @if($mainContent && $mainContent->content_type === 'pdf')
                            <div class="text-sm text-gray-500">Scroll pada area di atas untuk membaca materi PDF.</div>
                        @endif
                    </div>

                    <!-- Download PDF -->
                    @if($mainContent && $mainContent->content_type === 'pdf')
                        <div class="mt-8">
                            <div class="font-semibold text-gray-800 mb-2">Download materials</div>
                            <a href="{{ Storage::url($mainContent->content) }}" download
                               class="inline-flex items-center bg-indigo-100 hover:bg-indigo-200 text-indigo-800 px-4 py-2 rounded transition">
                                <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 4v12"/>
                                </svg>
                                Lesson presentation
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Comments Tab -->
                <div id="comments-tab" class="hidden text-gray-700">
                    <div class="mt-4">
                        <form action="{{ route('comments.store', $course) }}" method="POST" class="mb-6">
                            @csrf
                            <textarea name="content"
                                      class="w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-indigo-200"
                                      rows="3" placeholder="Tulis komentar..." required></textarea>
                            <button type="submit"
                                    class="mt-2 bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 transition">
                                Kirim Komentar
                            </button>
                        </form>
                        <div class="space-y-4">
                            @forelse($course->comments as $comment)
                                @include('comments.reply', ['comment' => $comment, 'depth' => 0])
                            @empty
                                <p class="text-gray-500">Belum ada komentar.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <script>
                function showTab(tab) {
                    const tabs = ['overview', 'comments'];
                    tabs.forEach(id => {
                        document.getElementById(`${id}-tab`).style.display = (id === tab) ? 'block' : 'none';
                        document.getElementById(`tab-${id}`).classList.toggle('border-indigo-600', id === tab);
                        document.getElementById(`tab-${id}`).classList.toggle('text-indigo-600', id === tab);
                        document.getElementById(`tab-${id}`).classList.toggle('border-transparent', id !== tab);
                        document.getElementById(`tab-${id}`).classList.toggle('text-gray-500', id !== tab);
                    });
                }
            </script>
        @else
            <!-- Not Enrolled -->
            <div class="p-8 text-center">
                <p class="text-indigo-300 text-lg font-semibold mb-4">Anda belum terdaftar di kursus ini.</p>
                <form action="{{ route('courses.enroll', $course) }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-block bg-gray-600 text-white px-6 py-2 rounded-lg font-semibold shadow hover:bg-indigo-700 transition">
                        Ikuti Kursus
                    </button>
                </form>
            </div>
        @endif
    @else
        <!-- Not Logged In -->
        <div class="p-8 text-center">
            <p class="text-red-400 text-lg font-semibold mb-4">Silakan login untuk melihat materi kursus dan berdiskusi.</p>
            <a href="{{ route('login') }}" class="inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg font-semibold shadow hover:bg-indigo-700 transition">Login</a>
        </div>
    @endauth
</div>
@endsection