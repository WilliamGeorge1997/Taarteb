<?php

namespace Modules\Student\App\Rules;

use Closure;
use Modules\Student\App\Models\Student;
use Illuminate\Contracts\Validation\ValidationRule;

class StudentBelongToSchool implements ValidationRule
{
    private $studentId;
    private $schoolId;
    public function __construct($studentId, $schoolId)
    {
        $this->studentId = $studentId;
        $this->schoolId = $schoolId;
    }
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $student = Student::find($this->studentId);
        if ($student->school_id != $this->schoolId) {
            $fail('The student does not belong to your school.');
        }
    }
}
