<?php

namespace Modules\Class\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Class\App\Models\Classroom;

class ClassDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = [
            ['title' => 'Class 1', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Class 2', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Class 3', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Class 4', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Class 5', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Class 6', 'created_at' => now(), 'updated_at' => now()],
        ];

        Classroom::insert($classes);
    }
}
