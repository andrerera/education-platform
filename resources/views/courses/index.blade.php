@extends('layouts.app')

@section('content')
<section class="bg-light pt-3 pb-5 px-3 px-sm-0 col-12">
    <div class="container d-flex flex-wrap justify-content-center px-0">
        <p class="text-center text-md-start text-primary col-12 mb-2" style="font-size: 0.825rem">
            Course
        </p>
        <h4 class="mb-4 col-12 text-center text-md-start" style="font-weight: 600">
            Popular Category for you
        </h4>
        
        <!-- Course Categories -->
        <div class="row justify-content-between w-100 h-100 mb-5">
            <div class="col-12 col-md-6 col-lg-3 mb-3">
                <a href="javascript:void(0);" class="card border-0 h-100 text-decoration-none">
                    <div class="pt-3 pb-3 px-3 text-start">
                        <img src="{{ asset('assets/design-course-2.png') }}" class="img-fluid" alt="UI/UX" 
                             style="object-fit: contain; object-position: center; width: 90px; height: 90px;" />
                    </div>
                    <div class="card-body px-3">
                        <h5 class="card-title mb-2">UI/UX Design</h5>
                        <p class="card-text" style="font-size: 0.875rem; color: #828282">
                            Learn the fundamentals of user interface and user experience design
                        </p>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-6 col-lg-3 mb-3">
                <a href="javascript:void(0);" class="card border-0 h-100 text-decoration-none">
                    <div class="pt-3 pb-3 px-3 text-start">
                        <img src="{{ asset('assets/code-course.png') }}" class="img-fluid" alt="Programming" 
                             style="object-fit: contain; object-position: center; width: 90px; height: 90px;" />
                    </div>
                    <div class="card-body px-3">
                        <h5 class="card-title mb-2">Programming</h5>
                        <p class="card-text" style="font-size: 0.875rem; color: #828282">
                            Master various programming languages and development frameworks
                        </p>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-6 col-lg-3 mb-3">
                <a href="javascript:void(0);" class="card border-0 h-100 text-decoration-none">
                    <div class="pt-3 pb-3 px-3 text-start">
                        <img src="{{ asset('assets/design-course-2.png') }}" class="img-fluid" alt="Design" 
                             style="object-fit: contain; object-position: center; width: 90px; height: 90px;" />
                    </div>
                    <div class="card-body px-3">
                        <h5 class="card-title mb-2">Graphic Design</h5>
                        <p class="card-text" style="font-size: 0.875rem; color: #828282">
                            Create stunning visual designs and digital artwork
                        </p>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-6 col-lg-3 mb-3">
                <a href="javascript:void(0);" class="card border-0 h-100 text-decoration-none">
                    <div class="pt-3 pb-3 px-3 text-start">
                        <img src="{{ asset('assets/cloud-course.png') }}" class="img-fluid" alt="Cloud" 
                             style="object-fit: contain; object-position: center; width: 90px; height: 90px;" />
                    </div>
                    <div class="card-body px-3">
                        <h5 class="card-title mb-2">Cloud Computing</h5>
                        <p class="card-text" style="font-size: 0.875rem; color: #828282">
                            Explore cloud technologies and infrastructure management
                        </p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Available Courses -->
        <div class="col-12">
            <h4 class="mb-4 text-center" style="font-weight: 600">
                Available Courses
            </h4>
            <div class="row">
                @foreach($courses as $course)
                    <div class="col-12 col-md-6 col-lg-4 mb-4">
                        <div class="card border-0 h-100" style="border-radius: 15px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                            @if($course->thumbnail)
                                <img src="{{ asset('storage/' . $course->thumbnail) }}" class="card-img-top" alt="{{ $course->title }}" 
                                     style="height: 200px; object-fit: cover; border-radius: 15px 15px 0 0;">
                            @else
                                <div class="card-img-top d-flex align-items-center justify-content-center" 
                                     style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px 15px 0 0;">
                                    <i class="fas fa-book text-white" style="font-size: 3rem;"></i>
                                </div>
                            @endif
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title mb-2">{{ Str::limit($course->title, 50) }}</h5>
                                <p class="card-text flex-grow-1" style="font-size: 0.875rem; color: #828282">
                                    {{ Str::limit($course->description, 100) }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <small class="text-muted">
                                        <i class="fas fa-user me-1"></i>
                                        {{ $course->creator->name }}
                                    </small>
                                    <a href="{{ route('courses.show', $course) }}" class="btn btn-primary btn-sm">
                                        View Course
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection