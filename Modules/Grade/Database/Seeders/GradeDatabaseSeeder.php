<?php

namespace Modules\Grade\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Grade\App\Models\Grade;

class GradeDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grades = [
            ['title' => 'Grade 1', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Grade 2', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Grade 3', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Grade 4', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Grade 5', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Grade 6', 'created_at' => now(), 'updated_at' => now()],
        ];

        Grade::insert($grades);
    }
}
