<section class="bg-light pt-3 pb-5 px-3 px-sm-0 col-12">
    <div class="d-flex flex-wrap align-items-center container pt-0 pb-5 py-lg-5">
        <div class="col-12 col-lg-6 d-flex justify-content-center justify-content-lg-end align-items-center position-relative mb-4">
            <img class="rocket-img width-xs-inherit" src="{{ asset('assets/rocket-fg.png') }}" alt="Rocket" />
            <img src="{{ asset('assets/rocket-bg.png') }}" class="position-absolute width-xs-inherit" alt="Rocket Background" />
        </div>
        <div class="col-12 col-lg-6 order-lg-first">
            <h1 class="mb-3">
                {{ $title ?? 'We help you find the best solutions for your learning needs.' }}
            </h1>
            <p class="mb-3">
                {{ $subtitle ?? 'Our courses are designed to provide you with the skills and knowledge necessary to succeed. Join us and discover a new way to learn and grow.' }}
            </p>
            <div class="d-flex flex-wrap align-items-center mt-5">
                <a class="btn btn-primary py-3 px-4 mb-3 me-2" href="{{ route('home') }}" style="font-weight: 600">
                    {{ $primaryButton['text'] ?? 'Explore Courses' }}
                </a>
                <button class="btn btn-transparent link-primary text-decoration-none d-flex align-items-center py-3 px-3 mb-3" 
                        data-bs-toggle="modal" data-bs-target="#videoModals">
                    <img src="{{ asset('assets/play.svg') }}" class="me-2" height="40" width="40" alt="Play" />
                    <strong>{{ $secondaryButton['text'] ?? 'Watch Video' }}</strong>
                </button>
            </div>
        </div>
    </div>
</section>

<section class="bg-light pt-3 pb-5 px-3 px-sm-0 col-12">
    <div class="container text-center d-flex flex-wrap justify-content-center">
        <h1 class="mb-4 col-12" style="font-weight: 600">
            All-in-One <span class="text-primary">Course Platform</span>
        </h1>
        <p class="col-12 col-md-6 mb-5" style="font-size: 0.825rem; color: #828282">
            Our courses are designed to provide you with the skills and knowledge necessary to succeed. Join us and discover a new way to learn and grow.
        </p>
        <div class="row justify-content-between w-100 h-100">
            <div class="col-12 col-md-4 mb-3 px-0 px-sm-3">
                <div class="card border-0 h-100" style="border-radius: 15px">
                    <div class="pt-3 pb-3">
                        <img src="{{ asset('assets/customer-care.png') }}" class="img-fluid" alt="Customer Care" 
                             style="object-fit: contain; object-position: center; width: 90px; height: 90px;" />
                    </div>
                    <div class="card-body px-4">
                        <h4 class="card-title mb-3">Fast Customer Care</h4>
                        <p class="card-text" style="font-size: 0.875rem; color: #828282">
                            Our dedicated support team is always ready to assist you with any inquiries or issues you may encounter, ensuring a seamless learning experience.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 mb-3 px-0 px-sm-3">
                <div class="card border-0 bg-primary text-white h-100" 
                     style="border-radius: 15px; box-shadow: 0px 42px 34px rgba(82, 67, 194, 0.295755);">
                    <div class="pt-3 pb-3">
                        <img src="{{ asset('assets/price-tag.png') }}" class="img-fluid" alt="Price Tag" 
                             style="object-fit: contain; object-position: center; width: 90px; height: 90px;" />
                    </div>
                    <div class="card-body px-4">
                        <h4 class="card-title mb-3">Affordable Prices</h4>
                        <p class="card-text" style="font-size: 0.875rem">
                            We offer competitive pricing for all our courses, making quality education accessible to everyone.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 mb-3 px-0 px-sm-3">
                <div class="card border-0 h-100" style="border-radius: 15px">
                    <div class="pt-3 pb-3">
                        <img src="{{ asset('assets/mentor.png') }}" class="img-fluid" alt="Mentor" 
                             style="object-fit: contain; object-position: center; width: 90px; height: 90px;" />
                    </div>
                    <div class="card-body px-4">
                        <h4 class="card-title mb-3">Professional Mentors</h4>
                        <p class="card-text" style="font-size: 0.875rem; color: #828282">
                            Learn from industry experts and experienced professionals who provide valuable insights and guidance throughout your learning journey.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>