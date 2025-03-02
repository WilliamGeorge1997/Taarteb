<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Admin\Database\Seeders\AdminDatabaseSeeder;
use Modules\Grade\Database\Seeders\GradeDatabaseSeeder;
use Modules\Subject\Database\Seeders\SubjectDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminDatabaseSeeder::class,
            SubjectDatabaseSeeder::class,
            GradeDatabaseSeeder::class,
        ]);
    }
}
