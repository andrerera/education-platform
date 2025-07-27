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
                            <!-- Handle YouTube URL -->
                            @if(Str::contains($mainContent->content, 'youtube.com') || Str::contains($mainContent->content, 'youtu.be'))
                                <div class="w-full md:w-3/4 mx-auto mt-3 mb-6 aspect-video">
                                    @php
                                        // Extract YouTube video ID
                                        if (Str::contains($mainContent->content, 'youtu.be/')) {
                                            $videoId = Str::after($mainContent->content, 'youtu.be/');
                                            $videoId = Str::before($videoId, '?');
                                        } else {
                                            $videoId = Str::after($mainContent->content, 'v=');
                                            $videoId = Str::before($videoId, '&');
                                        }
                                    @endphp
                                    <iframe class="w-full h-full rounded-xl"
                                            src="https://www.youtube.com/embed/{{ $videoId }}"
                                            frameborder="0" 
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen>
                                    </iframe>
                                </div>
                            @else
                                <!-- Other video URL - try direct embed -->
                                <div class="w-full md:w-3/4 mx-auto mt-3 mb-6">
                                    <video controls class="w-full rounded-xl">
                                        <source src="{{ $mainContent->content }}" type="video/mp4">
                                        Browser kamu tidak mendukung pemutar video.
                                    </video>
                                </div>
                            @endif
                        @else
                            <!-- Play Uploaded Video from Supabase -->
                            <div class="w-full md:w-3/4 mx-auto mt-3 mb-6">
                                <video controls class="w-full rounded-xl" preload="metadata">
                                    <source src="{{ StorageUrl::getPublicUrl($mainContent->content) }}" type="video/mp4">
                                    <source src="{{ StorageUrl::getPublicUrl($mainContent->content) }}" type="video/webm">
                                    <source src="{{ StorageUrl::getPublicUrl($mainContent->content) }}" type="video/ogg">
                                    Browser kamu tidak mendukung pemutar video.
                                </video>
                            </div>
                        @endif
                    </div>
                
                @elseif($mainContent && $mainContent->content_type === 'pdf')
                    <div class="mt-6">
                        <h2 class="text-lg font-bold mb-2">Materi PDF</h2>
                        <div class="rounded-xl overflow-hidden mb-6 bg-gray-100 border">
                            <iframe src="{{ StorageUrl::getPublicUrl($mainContent->content) }}" 
                                    class="w-full h-[400px] md:h-[600px]" 
                                    frameborder="0"
                                    title="PDF Viewer">
                            </iframe>
                        </div>
                        <!-- Alternative PDF viewer for mobile -->
                        <div class="md:hidden text-center">
                            <p class="text-sm text-gray-600 mb-2">Jika PDF tidak tampil dengan baik, gunakan tombol download di bawah.</p>
                        </div>
                    </div>

                @elseif($mainContent && $mainContent->content_type === 'audio')
                    <div class="mt-6">
                        <h2 class="text-lg font-bold mb-2">Materi Audio</h2>
                        <div class="bg-gray-50 p-6 rounded-xl mb-6">
                            <audio controls class="w-full">
                                <source src="{{ StorageUrl::getPublicUrl($mainContent->content) }}" type="audio/mpeg">
                                <source src="{{ StorageUrl::getPublicUrl($mainContent->content) }}" type="audio/wav">
                                Browser kamu tidak mendukung pemutar audio.
                            </audio>
                        </div>
                    </div>

                @elseif($mainContent && $mainContent->content_type === 'article')
                    <div class="mt-6">
                        <h2 class="text-lg font-bold mb-2">Artikel</h2>
                        <div class="bg-gray-50 p-6 rounded-xl mb-6 prose max-w-none">
                            {!! nl2br(e($mainContent->content)) !!}
                        </div>
                    </div>
                @endif

                <!-- Instructors -->
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 rounded-full bg-indigo-600 text-white flex items-center justify-center text-xl font-bold">
                        {{ strtoupper(substr($course->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Instructor / Creator</div>
                        <div class="font-semibold text-gray-900">{{ $course->user->name }}</div>
                        <div class="text-sm text-gray-600">{{ $course->user->email }}</div>
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
                        @if($mainContent)
                            <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                                <div class="text-sm font-medium text-blue-800">Tipe Materi:</div>
                                <div class="text-sm text-blue-600 capitalize">{{ $mainContent->content_type }}</div>
                            </div>
                        @endif
                    </div>

                    <!-- Download Materials -->
                    @if($mainContent && in_array($mainContent->content_type, ['pdf', 'audio']))
                        @php
                            $fileName = basename($mainContent->content);
                            $fileType = $mainContent->content_type === 'pdf' ? 'PDF Document' : 'Audio File';
                        @endphp
                        <div class="mt-8">
                            <div class="font-semibold text-gray-800 mb-2">Download Materials</div>
                            <a href="{{ StorageUrl::getPublicUrl($mainContent->content) }}" 
                               download="{{ $fileName }}"
                               target="_blank"
                               class="inline-flex items-center bg-indigo-100 hover:bg-indigo-200 text-indigo-800 px-4 py-2 rounded transition">
                                <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 4v12"/>
                                </svg>
                                {{ $fileType }}
                            </a>
                        </div>
                    @elseif($mainContent && $mainContent->content_type === 'video' && !Str::startsWith($mainContent->content, 'http'))
                        @php
                            $fileName = basename($mainContent->content);
                        @endphp
                        <div class="mt-8">
                            <div class="font-semibold text-gray-800 mb-2">Download Video</div>
                            <a href="{{ StorageUrl::getPublicUrl($mainContent->content) }}" 
                               download="{{ $fileName }}"
                               target="_blank"
                               class="inline-flex items-center bg-indigo-100 hover:bg-indigo-200 text-indigo-800 px-4 py-2 rounded transition">
                                <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 4v12"/>
                                </svg>
                                Video File
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
                                      class="w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-indigo-200 focus:border-indigo-500"
                                      rows="3" 
                                      placeholder="Tulis komentar..." 
                                      required></textarea>
                            <button type="submit"
                                    class="mt-2 bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 transition">
                                Kirim Komentar
                            </button>
                        </form>
                        <div class="space-y-4">
                            @forelse($course->comments as $comment)
                                @include('comments.reply', ['comment' => $comment, 'depth' => 0])
                            @empty
                                <p class="text-gray-500 text-center py-8">Belum ada komentar. Jadilah yang pertama berkomentar!</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <script>
                function showTab(tab) {
                    const tabs = ['overview', 'comments'];
                    tabs.forEach(id => {
                        const tabContent = document.getElementById(`${id}-tab`);
                        const tabButton = document.getElementById(`tab-${id}`);
                        
                        if (id === tab) {
                            tabContent.style.display = 'block';
                            tabButton.classList.add('border-indigo-600', 'text-indigo-600');
                            tabButton.classList.remove('border-transparent', 'text-gray-500');
                        } else {
                            tabContent.style.display = 'none';
                            tabButton.classList.remove('border-indigo-600', 'text-indigo-600');
                            tabButton.classList.add('border-transparent', 'text-gray-500');
                        }
                    });
                }

                // Handle video loading errors
                document.addEventListener('DOMContentLoaded', function() {
                    const videos = document.querySelectorAll('video');
                    videos.forEach(video => {
                        video.addEventListener('error', function() {
                            const errorDiv = document.createElement('div');
                            errorDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded';
                            errorDiv.innerHTML = 'Maaf, video tidak dapat dimuat. <a href="' + video.src + '" target="_blank" class="underline">Coba buka langsung</a>';
                            video.parentNode.replaceChild(errorDiv, video);
                        });
                    });

                    // Handle PDF loading for mobile
                    const iframes = document.querySelectorAll('iframe[src*=".pdf"]');
                    iframes.forEach(iframe => {
                        iframe.addEventListener('error', function() {
                            const errorDiv = document.createElement('div');
                            errorDiv.className = 'bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded text-center';
                            errorDiv.innerHTML = 'PDF tidak dapat ditampilkan. <a href="' + iframe.src + '" target="_blank" class="underline font-semibold">Buka PDF di tab baru</a>';
                            iframe.parentNode.replaceChild(errorDiv, iframe);
                        });
                    });
                });
            </script>

        @else
            <!-- Not Enrolled -->
            <div class="p-8 text-center">
                <div class="max-w-md mx-auto">
                    @if($course->thumbnail && $course->thumbnail !== '-')
                        <img src="{{ StorageUrl::getPublicUrl($course->thumbnail) }}" 
                             alt="{{ $course->title }}" 
                             class="w-full rounded-xl shadow-lg mb-6">
                    @endif
                    <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $course->title }}</h1>
                    <p class="text-gray-600 mb-6">{{ $course->description }}</p>
                    <p class="text-indigo-600 text-lg font-semibold mb-4">Daftar sekarang untuk mengakses materi kursus!</p>
                    <form action="{{ route('courses.enroll', $course) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-block bg-indigo-600 text-white px-8 py-3 rounded-lg font-semibold shadow hover:bg-indigo-700 transition-all duration-200 transform hover:scale-105">
                            🎓 Ikuti Kursus Gratis
                        </button>
                    </form>
                </div>
            </div>
        @endif
    @else
        <!-- Not Logged In -->
        <div class="p-8 text-center">
            <div class="max-w-md mx-auto">
                @if($course->thumbnail && $course->thumbnail !== '-')
                    <img src="{{ StorageUrl::getPublicUrl($course->thumbnail) }}" 
                         alt="{{ $course->title }}" 
                         class="w-full rounded-xl shadow-lg mb-6">
                @endif
                <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $course->title }}</h1>
                <p class="text-gray-600 mb-6">{{ $course->description }}</p>
                <p class="text-red-500 text-lg font-semibold mb-4">Silakan login untuk melihat materi kursus dan berdiskusi.</p>
                <a href="{{ route('login') }}" class="inline-block bg-indigo-600 text-white px-8 py-3 rounded-lg font-semibold shadow hover:bg-indigo-700 transition-all duration-200 transform hover:scale-105">
                    🔐 Login Sekarang
                </a>
            </div>
        </div>
    @endauth
</div>
@endsection