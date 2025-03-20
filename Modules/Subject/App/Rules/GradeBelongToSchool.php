<?php

namespace Modules\Subject\App\Rules;

use Closure;
use Modules\Grade\App\Models\Grade;
use Illuminate\Contracts\Validation\ValidationRule;

class GradeBelongToSchool implements ValidationRule
{
    private $gradeId;
    private $schoolId;
    public function __construct($gradeId, $schoolId)
    {
        $this->gradeId = $gradeId;
        $this->schoolId = $schoolId;
    }
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $grade = Grade::find($this->gradeId);
        if (!$grade) {
            $fail('The grade does not exist.');
        } else if ($grade->school_id != $this->schoolId) {
            $fail('The grade does not belong to your school.');
        }
    }
}