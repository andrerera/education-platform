// Loading animation
document.addEventListener('DOMContentLoaded', function() {
    // Hide loading spinner after page loads
    setTimeout(function() {
        const spanner = document.querySelector('.spanner');
        if (spanner) {
            spanner.classList.remove('show');
        }
    }, 1000);

    // Music toggle functionality
    const musicToggle = document.getElementById('music-toggle');
    const backgroundMusic = document.getElementById('background-music');
    const toggleIcon = document.getElementById('toggle-icon');

    if (musicToggle && backgroundMusic && toggleIcon) {
        musicToggle.addEventListener('click', function() {
            if (backgroundMusic.paused) {
                backgroundMusic.play();
                toggleIcon.classList.remove('fa-play');
                toggleIcon.classList.add('fa-pause');
            } else {
                backgroundMusic.pause();
                toggleIcon.classList.remove('fa-pause');
                toggleIcon.classList.add('fa-play');
            }
        });
    }

    // Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Bootstrap popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
});

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Navbar scroll effect
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        if (window.scrollY > 50) {
            navbar.classList.add('navbar-scrolled');
        } else {
            navbar.classList.remove('navbar-scrolled');
        }
    }
});
