<?php

namespace App\Imports;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Student\App\Models\Student;
use Illuminate\Support\Facades\Validator;
use Modules\Student\App\Rules\MaxStudents;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Modules\Class\App\Rules\ClassBelongToSchool;
use Modules\Subject\App\Rules\GradeBelongToSchool;
use Illuminate\Http\Exceptions\HttpResponseException;

class StudentsImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        try {
            foreach ($rows as $index => $row) {
                // Base validation rules
                $rules = [
                    'name' => ['required', 'max:255'],
                    'email' => ['required', 'email', 'unique:students,email', 'unique:students,parent_email', 'different:parent_email'],
                    'identity_number' => ['required'],
                    'gender' => ['required', 'in:m,f'],
                    'parent_email' => ['nullable', 'email', 'unique:students,parent_email', 'unique:students,email', 'different:email'],
                    'parent_phone' => ['required'],
                ];

                // Add rules based on user role
                if (auth('user')->user()->hasRole('Super Admin')) {
                    $rules['school_id'] = ['required', 'exists:schools,id'];
                    $rules['grade_id'] = ['required', 'exists:grades,id', new GradeBelongToSchool( $row['school_id'])];
                    $rules['class_id'] = ['required', 'exists:classes,id', new ClassBelongToSchool($row['class_id'], $row['school_id']), new MaxStudents($row['class_id'])];
                } elseif (auth('user')->user()->hasRole('School Manager')) {
                    $schoolId = auth('user')->user()->school_id;
                    $rules['school_id'] = ['prohibited'];
                    $rules['grade_id'] = ['required', 'exists:grades,id', new GradeBelongToSchool( $schoolId)];
                    $rules['class_id'] = ['required', 'exists:classes,id', new ClassBelongToSchool($row['class_id'], $schoolId), new MaxStudents($row['class_id'])];
                }

                // Validate each row
                $validator = Validator::make($row->toArray(), $rules);

                if ($validator->fails()) {
                    throw new HttpResponseException(
                        returnValidationMessage(
                            false,
                            trans('validation.rules_failed'),
                            ['row' => $index + 1, 'errors' => $validator->errors()->messages()],
                            'unprocessable_entity'
                        )
                    );
                }
                $data = [
                    'name' => $row['name'],
                    'gender' => $row['gender'],
                    'email' => $row['email'],
                    'identity_number' => $row['identity_number'],
                    'parent_email' => $row['parent_email'] ?? null,
                    'parent_phone' => $row['parent_phone'],
                    'grade_id' => $row['grade_id'],
                    'class_id' => $row['class_id'],
                    'is_graduated' => 0,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (auth('user')->user()->hasRole('Super Admin')) {
                    $data['school_id'] = $row['school_id'];
                } elseif (auth('user')->user()->hasRole('School Manager')) {
                    $data['school_id'] = auth('user')->user()->school_id;
                }

                Student::create($data);
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}