<?php

namespace Modules\Subject\App\Rules;

use Closure;
use Modules\Grade\App\Models\Grade;
use Illuminate\Contracts\Validation\ValidationRule;

class GradeBelongToSchool implements ValidationRule
{
    private $gradeId;
    public function __construct($gradeId)
    {
        $this->gradeId = $gradeId;
    }
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $grade = Grade::find($this->gradeId);
        if ($grade->school_id != auth('user')->user()->school_id) {
            $fail('The grade does not belong to your school.');
        }
    }
}
