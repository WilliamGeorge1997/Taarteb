<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// use Modules\Grade\Database\Seeders\GradeDatabaseSeeder;
use Modules\Subject\Database\Seeders\SubjectDatabaseSeeder;
use Modules\User\Database\Seeders\UserDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserDatabaseSeeder::class,
            // GradeDatabaseSeeder::class,
            SubjectDatabaseSeeder::class,
        ]);
    }
}
