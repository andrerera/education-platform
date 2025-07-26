<?php
namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create roles with specific IDs to match previous setup
        Role::updateOrCreate(
            ['id' => 1, 'name' => 'admin'],
            ['guard_name' => 'web']
        );
        Role::updateOrCreate(
            ['id' => 2, 'name' => 'user'],
            ['guard_name' => 'web']
        );
        Role::updateOrCreate(
            ['id' => 3, 'name' => 'creator'],
            ['guard_name' => 'web']
        );

        // Create admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role_id' => 1,
        ]);
        $admin->assignRole('admin');

        // Create regular user
        $user = User::create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'role_id' => 2,
        ]);
        $user->assignRole('user');

        // Create creator user (to restore user ID 7 or similar)
        $creator = User::create([
            'name' => 'Test Creator',
            'email' => 'creator@example.com',
            'password' => bcrypt('password'),
            'role_id' => 3,
        ]);
        $creator->assignRole('creator');

        // Create sample course
        Course::create([
            'title' => 'Sample Course',
            'description' => 'This is a test course',
            'thumbnail' => 'thumbnails/sample.jpg', // Ensure this file exists
            'user_id' => $admin->id,
            'status' => 'approved',
        ]);
    }
}