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
            ['name' => 'Mathematics', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'English', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Science', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'History', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Geography', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Physics', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Chemistry', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Biology', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Computer Science', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Art', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Physical Education', 'created_at' => now(), 'updated_at' => now()],
        ];

        Subject::insert($subjects);
    }
}
