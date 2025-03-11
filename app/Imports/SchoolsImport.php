<?php

namespace App\Imports;

use Exception;
use Modules\User\App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\School\App\Models\School;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class SchoolsImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                $existingSchoolByPhone = User::where('phone', $row['phone'])->first();
                if ($existingSchoolByPhone) {
                    continue;
                }

                $existingSchoolByEmail = User::where('email', $row['email'])->first();
                if ($existingSchoolByEmail) {
                    continue;
                }

                $school = School::create([
                    'name' => $row['name'],
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $schoolManager = User::create([
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'phone' => $row['phone'],
                    'password' => Hash::make($row['password']),
                    'role' => 'School Manager',
                    'school_id' => $school->id,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $schoolManager->assignRole('School Manager');
                // $gradesArray = explode(',', $row['grades']);
                // $school->grades()->sync(array_filter($gradesArray));
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}
