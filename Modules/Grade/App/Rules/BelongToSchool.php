<?php

namespace Modules\Grade\App\Rules;

use Closure;
use Modules\Grade\App\Models\GradeCategory;
use Illuminate\Contracts\Validation\ValidationRule;

class BelongToSchool implements ValidationRule
{
    private $gradeCategoryId;
    public function __construct($gradeCategoryId)
    {
        $this->gradeCategoryId = $gradeCategoryId;
    }
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $gradeCategory = GradeCategory::find($this->gradeCategoryId);
        if ($gradeCategory->school_id != auth('user')->user()->school_id) {
            $fail('The grade category does not belong to your school.');
        }
    }
}
