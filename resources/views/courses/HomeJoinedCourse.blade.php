@extends('layouts.app')

@section('content')
<section class="bg-light pt-3 pb-5 px-3 px-sm-0 col-12">
    <div class="container">
        <!-- Enrolled Courses Section -->
        <div class="mb-5">
            <h2 class="text-primary mb-4" style="font-weight: 600">
                <i class="fas fa-graduation-cap me-2"></i>
                My Enrolled Courses
            </h2>
            
            @if($enrolledCourses->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-book-open text-muted mb-3" style="font-size: 3rem;"></i>
                    <h4 class="text-muted mb-3">No Courses Yet</h4>
                    <p class="text-muted mb-4">You haven't enrolled in any courses yet.</p>
                    <a href="{{ route('courses.index') }}" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>
                        Explore Courses
                    </a>
                </div>
            @else
                <div class="row">
                    @foreach($enrolledCourses as $course)
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <div class="card border-0 h-100 position-relative" style="border-radius: 15px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                                <!-- Enrolled Badge -->
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>
                                        Enrolled
                                    </span>
                                </div>
                                
                                @if($course->thumbnail && $course->thumbnail !== '-')
                                    <img src="{{ asset('storage/'.$course->thumbnail) }}" class="card-img-top" alt="{{ $course->title }}" 
                                         style="height: 200px; object-fit: cover; border-radius: 15px 15px 0 0;">
                                @else
                                    <div class="card-img-top d-flex align-items-center justify-content-center" 
                                         style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px 15px 0 0;">
                                        <i class="fas fa-book text-white" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                                
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title mb-2">{{ $course->title }}</h5>
                                    <p class="card-text flex-grow-1 text-muted" style="font-size: 0.875rem;">
                                        {{ Str::limit($course->description, 100) }}
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <small class="text-muted">
                                            <i class="fas fa-user me-1"></i>
                                            {{ $course->creator->name }}
                                        </small>
                                        <a href="{{ route('courses.show', $course) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-play me-1"></i>
                                            Continue Learning
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Recommended Courses Section -->
        <div class="mb-5">
            <h2 class="text-primary mb-4" style="font-weight: 600">
                <i class="fas fa-star me-2"></i>
                Recommended Courses
            </h2>
            
            @if($unjoinedCourses->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-trophy text-muted mb-3" style="font-size: 3rem;"></i>
                    <h4 class="text-muted mb-3">All Caught Up!</h4>
                    <p class="text-muted">You've enrolled in all available courses.</p>
                </div>
            @else
                <div class="row">
                    @foreach($unjoinedCourses as $course)
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <div class="card border-0 h-100" style="border-radius: 15px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                                @if($course->thumbnail && $course->thumbnail !== '-')
                                    <img src="{{ asset('storage/'.$course->thumbnail) }}" class="card-img-top" alt="{{ $course->title }}" 
                                         style="height: 200px; object-fit: cover; border-radius: 15px 15px 0 0;">
                                @else
                                    <div class="card-img-top d-flex align-items-center justify-content-center" 
                                         style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px 15px 0 0;">
                                        <i class="fas fa-book text-white" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                                
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title mb-2">{{ $course->title }}</h5>
                                    <p class="card-text flex-grow-1 text-muted" style="font-size: 0.875rem;">
                                        {{ Str::limit($course->description, 100) }}
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <small class="text-muted">
                                            <i class="fas fa-user me-1"></i>
                                            {{ $course->creator->name }}
                                        </small>
                                        <form action="{{ route('courses.enroll', $course) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-plus me-1"></i>
                                                Enroll
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>
@endsection