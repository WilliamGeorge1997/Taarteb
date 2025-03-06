<?php

namespace Modules\Student\App\Rules;

use Closure;
use Modules\Class\App\Models\Classroom;
use Modules\Student\App\Models\Student;
use Illuminate\Contracts\Validation\ValidationRule;

class MaxStudents implements ValidationRule
{
    private $classId;
    public function __construct($classId)
    {
        $this->classId = $classId;
    }
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $maxStudents = Classroom::find($this->classId)->max_students;
        $studentsCount = Student::where('class_id', $this->classId)->count();
        if ($studentsCount >= $maxStudents) {
            $fail('The class has reached its maximum number of students.');
        }
    }
}
