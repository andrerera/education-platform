@php use Illuminate\Support\Str; @endphp

@extends('layouts.app')

@section('content')
<div class="bg-light pt-3 pb-5 px-3 px-sm-0 col-12">
    <div class="container d-flex flex-wrap justify-content-center px-0">
        @auth
            @if($isEnrolled)
                @php
                    $mainContent = $contents->first();
                @endphp

                <!-- Course Video/Content Section -->
                <div class="col-12 col-lg-8 mb-4 mb-lg-0">
                    <div class="card border-0" style="border-radius: 15px;">
                        <div class="card-body p-0">
                            @if($mainContent && $mainContent->content_type === 'video')
                                <div class="position-relative">
                                    @if(Str::startsWith($mainContent->content, 'http'))
                                        <!-- Embed YouTube -->
                                        <div class="ratio ratio-16x9">
                                            <iframe src="https://www.youtube.com/embed/{{ Str::after($mainContent->content, 'v=') }}"
                                                    frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                    allowfullscreen style="border-radius: 15px 15px 0 0;"></iframe>
                                        </div>
                                    @else
                                        <!-- Play Uploaded Video -->
                                        <video controls class="w-100" style="border-radius: 15px 15px 0 0;">
                                            <source src="{{ asset('storage/' . $mainContent->content) }}" type="video/mp4">
                                            Browser kamu tidak mendukung pemutar video.
                                        </video>
                                    @endif
                                </div>
                            @elseif($mainContent && $mainContent->content_type === 'pdf')
                                <div class="ratio ratio-16x9" style="border-radius: 15px 15px 0 0; overflow: hidden;">
                                    <iframe src="{{ Storage::url($mainContent->content) }}" class="w-100 h-100" frameborder="0"></iframe>
                                </div>
                            @else
                                <div class="d-flex align-items-center justify-content-center" style="height: 400px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px 15px 0 0;">
                                    <div class="text-center text-white">
                                        <i class="fas fa-play-circle" style="font-size: 4rem; margin-bottom: 1rem;"></i>
                                        <h4>Course Content</h4>
                                        <p>Content will be available here</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Course Info Sidebar -->
                <div class="col-12 col-lg-4 px-3">
                    <div class="card border-0" style="border-radius: 15px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                        <div class="card-body">
                            <h5 class="card-title mb-3">{{ $course->title }}</h5>
                            
                            <!-- Course Progress -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">Progress</span>
                                    <span class="text-primary">0%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 0%"></div>
                                </div>
                            </div>

                            <!-- Course Content List -->
                            <div class="mb-4">
                                <h6 class="mb-3">Course Content</h6>
                                <ul class="list-group list-group-flush">
                                    @foreach($contents as $index => $content)
                                        <li class="list-group-item bg-transparent px-0 py-2 border-0">
                                            <a href="#" class="row justify-content-start text-decoration-none align-items-center">
                                                <div class="col-auto">
                                                    <strong class="fs-4 mb-0" style="color: #9c9c9c;">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</strong>
                                                </div>
                                                <div class="col">
                                                    <p class="mb-0" style="font-size: .9rem;">
                                                        {{ $content->title ?? 'Lesson ' . ($index + 1) }}
                                                    </p>
                                                    <small class="text-muted">{{ $content->content_type ?? 'Content' }}</small>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fas fa-play-circle text-primary"></i>
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <!-- Instructor Info -->
                            <div class="border-top pt-3">
                                <h6 class="mb-3">Instructor</h6>
                                <div class="d-flex align-items-center">
                                    <div class="w-12 h-12 rounded-full bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                        {{ strtoupper(substr($course->creator->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $course->creator->name }}</div>
                                        <small class="text-muted">{{ $course->creator->email }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Course Details Tabs -->
                <div class="col-12 mt-4">
                    <div class="card border-0" style="border-radius: 15px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                        <div class="card-body">
                            <ul class="nav nav-tabs border-0" id="courseTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
                                        Overview
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="comments-tab" data-bs-toggle="tab" data-bs-target="#comments" type="button" role="tab">
                                        Comments
                                    </button>
                                </li>
                            </ul>
                            
                            <div class="tab-content mt-4" id="courseTabsContent">
                                <!-- Overview Tab -->
                                <div class="tab-pane fade show active" id="overview" role="tabpanel">
                                    <div class="mb-4">
                                        <h5>Course Description</h5>
                                        <p class="text-muted">{{ $course->description }}</p>
                                    </div>
                                    
                                    @if($mainContent && $mainContent->content_type === 'pdf')
                                        <div class="mb-4">
                                            <h5>Download Materials</h5>
                                            <a href="{{ Storage::url($mainContent->content) }}" download class="btn btn-outline-primary">
                                                <i class="fas fa-download me-2"></i>
                                                Download PDF
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Comments Tab -->
                                <div class="tab-pane fade" id="comments" role="tabpanel">
                                    <div class="mb-4">
                                        <h5>Add Comment</h5>
                                        <form action="{{ route('comments.store', $course) }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <textarea name="content" class="form-control" rows="3" placeholder="Share your thoughts..." required></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary">
                                                Post Comment
                                            </button>
                                        </form>
                                    </div>
                                    
                                    <div class="comments-section">
                                        <h5>Comments</h5>
                                        @forelse($course->comments as $comment)
                                            @include('comments.reply', ['comment' => $comment, 'depth' => 0])
                                        @empty
                                            <p class="text-muted">No comments yet. Be the first to comment!</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @else
                <!-- Not Enrolled -->
                <div class="col-12">
                    <div class="card border-0 text-center" style="border-radius: 15px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                        <div class="card-body py-5">
                            <i class="fas fa-lock text-muted mb-3" style="font-size: 3rem;"></i>
                            <h4 class="mb-3">Course Locked</h4>
                            <p class="text-muted mb-4">You need to enroll in this course to access the content.</p>
                            <form action="{{ route('courses.enroll', $course) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-graduation-cap me-2"></i>
                                    Enroll Now
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @else
            <!-- Not Logged In -->
            <div class="col-12">
                <div class="card border-0 text-center" style="border-radius: 15px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <div class="card-body py-5">
                        <i class="fas fa-user-lock text-muted mb-3" style="font-size: 3rem;"></i>
                        <h4 class="mb-3">Login Required</h4>
                        <p class="text-muted mb-4">Please login to view course content and enroll in courses.</p>
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Login
                        </a>
                    </div>
                </div>
            </div>
        @endauth
    </div>
</div>
@endsection