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
            ['name' => 'Grade 1', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Grade 2', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Grade 3', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Grade 4', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Grade 5', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Grade 6', 'created_at' => now(), 'updated_at' => now()],
        ];

        Grade::insert($grades);
    }
}
