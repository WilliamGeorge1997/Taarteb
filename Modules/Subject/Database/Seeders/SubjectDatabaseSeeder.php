<?php

namespace Modules\Subject\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Subject\App\Models\Subject;

class SubjectDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            ['title' => 'Mathematics', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'English', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Science', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'History', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Geography', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Physics', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Chemistry', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Biology', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Computer Science', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Art', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Physical Education', 'created_at' => now(), 'updated_at' => now()],
        ];

        Subject::insert($subjects);
    }
}
