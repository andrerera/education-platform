```
└── 📁education-platform
    └── 📁app
        └── 📁Http
            └── 📁Controllers
                ├── AdminController.php
                ├── AuthController.php
                ├── CommentController.php
                ├── Controller.php
                ├── CourseController.php
                ├── HomeJoinedCourseController.php
            ├── Kernel.php
        └── 📁Models
            ├── Comment.php
            ├── Course.php
            ├── CourseContent.php
            ├── Review.php
            ├── Role.php
            ├── User.php
        └── 📁Providers
            ├── AppServiceProvider.php
    └── 📁bootstrap
        └── 📁cache
            ├── .gitignore
            ├── packages.php
            ├── services.php
        ├── app.php
        ├── providers.php
    └── 📁config
        ├── app.php
        ├── auth.php
        ├── cache.php
        ├── database.php
        ├── filesystems.php
        ├── logging.php
        ├── mail.php
        ├── permission.php
        ├── queue.php
        ├── services.php
        ├── session.php
    └── 📁database
        └── 📁factories
            ├── UserFactory.php
        └── 📁migrations
            ├── 0001_01_01_000001_create_cache_table.php
            ├── 0001_01_01_000002_create_jobs_table.php
            ├── 2025_07_24_000001_create_roles_table.php
            ├── 2025_07_24_000002_create_users_table.php
            ├── 2025_07_24_000003_create_courses_table.php
            ├── 2025_07_24_000004_create_course_contents_table.php
            ├── 2025_07_24_000005_create_comments_table.php
            ├── 2025_07_24_020401_create_permission_tables.php
            ├── 2025_07_24_021730_create_sessions_table.php
            ├── 2025_07_24_063951_create_course_user_table.php
            ├── 2025_07_24_064707_add_order_to_course_contents_table.php
            ├── 2025_07_24_083257_remove_role_id_from_users_table.php
        └── 📁seeders
            ├── AdminSeeder.php
            ├── DatabaseSeeder.php
        ├── .gitignore
        ├── database.sqlite
    └── 📁public
        └── 📁storage
            └── 📁contents
                ├── cStc0QPQWMutcQwS4JN2s1Lag73baMMgR6xmO1IK.mp4
            └── 📁pdfs
                ├── MuRJSB8ox1rjpJpyFBrRWsd1OZbXri8dKqz5r6xM.pdf
                ├── nBsez7APLeIBATwZdBCtN2eMGGAKtZkCxAwpMJPC.pdf
            └── 📁thumbnails
                ├── JIEaF7O6vHsV0y7nweEkkPrGRbbaCd3WwJAbAn4A.jpg
                ├── s1cRkz2XGwOSHuAVB6qT0eSTKlJqy5MufvJfCLmk.jpg
            ├── .gitignore
        ├── .htaccess
        ├── favicon.ico
        ├── hot
        ├── index.php
        ├── robots.txt
    └── 📁resources
        └── 📁css
            ├── app.css
        └── 📁js
            ├── app.js
            ├── bootstrap.js
        └── 📁views
            └── 📁admin
                ├── dashboard.blade.php
            └── 📁auth
                ├── login_admin.blade.php
                ├── login.blade.php
                ├── register.blade.php
            └── 📁comments
                ├── reply.blade.php
            └── 📁components
                ├── course-card.blade.php
                ├── footer.blade.php
                ├── hero-section.blade.php
                ├── navbar.blade.php
                ├── start-course-button.blade.php
            └── 📁courses
                ├── create.blade.php
                ├── HomeJoinedCourse.blade.php
                ├── index.blade.php
                ├── my-submissions.blade.php
                ├── show.blade.php
            └── 📁layouts
                ├── app.blade.php
                ├── guest.blade.php
    └── 📁routes
        ├── console.php
        ├── web.php
    └── 📁storage
        └── 📁app
            └── 📁private
                ├── .gitignore
            └── 📁public
                └── 📁contents
                    ├── cStc0QPQWMutcQwS4JN2s1Lag73baMMgR6xmO1IK.mp4
                └── 📁pdfs
                    ├── MuRJSB8ox1rjpJpyFBrRWsd1OZbXri8dKqz5r6xM.pdf
                    ├── nBsez7APLeIBATwZdBCtN2eMGGAKtZkCxAwpMJPC.pdf
                └── 📁thumbnails
                    ├── JIEaF7O6vHsV0y7nweEkkPrGRbbaCd3WwJAbAn4A.jpg
                    ├── s1cRkz2XGwOSHuAVB6qT0eSTKlJqy5MufvJfCLmk.jpg
                ├── .gitignore
            ├── .gitignore