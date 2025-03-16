<?php

namespace Modules\Student\App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Modules\Student\App\Models\Student;

class StudentBelongToSchool implements ValidationRule
{
    protected $studentId;
    protected $schoolId;

    public function __construct($studentId, $schoolId)
    {
        $this->studentId = $studentId;
        $this->schoolId = $schoolId;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $student = Student::find($this->studentId);

        if (!$student || $student->school_id !== $this->schoolId) {
            $fail('The selected student does not belong to your school.');
        }
    }
}