<?php

namespace Modules\Subject\App\Rules;

use Closure;
use Modules\Subject\App\Models\Subject;
use Illuminate\Contracts\Validation\ValidationRule;

class SubjectBelongToSchool implements ValidationRule
{
    private $subjectId;
    private $schoolId;
    public function __construct($subjectId, $schoolId)
    {
        $this->subjectId = $subjectId;
        $this->schoolId = $schoolId;
    }
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $subject = Subject::find($this->subjectId);
        if (!$subject || $subject->school_id != $this->schoolId) {
            $fail('The subject does not belong to your school.');
        }
    }
}
