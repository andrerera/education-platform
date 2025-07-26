<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ config('app.name', 'Kyulearn') }} - Digital Courses</title>

    <link rel="icon" href="{{ asset('icons/favicon.ico') }}" sizes="48x48">
    <link rel="icon" href="{{ asset('icons/favicon-32x32.png') }}" sizes="32x32" type="image/png">
    <link rel="icon" href="{{ asset('icons/favicon-16x16.png') }}" sizes="16x16" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('icons/apple-touch-icon.png') }}">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-5/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('icons/logo-circle.png') }}" type="image/x-icon">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('vendor/tiny-slider/tiny-slider.css') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="spanner d-flex flex-column show">
        <div class="m-auto">
            <div class="loader"></div>
            <p>Loading, Please Wait..</p>
        </div>
    </div>

    <!-- Audio Tag -->
    <audio id="background-music" class="d-none" src="{{ asset('assets/bg-music.mp3') }}" autoplay loop></audio>

    <!-- Play/Pause Toggle Button -->
    <div id="music-toggle" class="music-toggle">
        <i id="toggle-icon" class="fas fa-pause"></i>
    </div>
    
    <!-- Navbar -->
    @include('components.navbar')

    <!-- Hero Section -->
    @if(!auth()->check())
        @if(View::hasSection('hero'))
            @yield('hero')
        @else
            @include('components.hero-section')
        @endif
    @endif

    <!-- Content -->
    <main>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    @include('components.footer')

    <!-- Scripts -->
    <script src="{{ asset('vendor/bootstrap-5/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendor/tiny-slider/tiny-slider.js') }}"></script>
    <script src="{{ asset('vendor/isotope/isotope.min.js') }}"></script>
    <script src="{{ asset('vendor/js/main.js') }}"></script>

    <script>
        // Mobile menu JS
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileMenuClose = document.getElementById('mobile-menu-close');

            if (mobileMenuButton && mobileMenu && mobileMenuClose) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.remove('hidden');
                });

                mobileMenuClose.addEventListener('click', function() {
                    mobileMenu.classList.add('hidden');
                });

                document.addEventListener('click', function(event) {
                    if (!mobileMenu.contains(event.target) && !mobileMenuButton.contains(event.target)) {
                        mobileMenu.classList.add('hidden');
                    }
                });
            }
        });
    </script>

    @yield('scripts')
</body>
</html>