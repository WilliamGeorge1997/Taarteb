<?php

namespace Modules\Session\App\Rules;

use Closure;
use Modules\Class\App\Models\Classroom;
use Modules\Session\App\Models\Session;
use Illuminate\Contracts\Validation\ValidationRule;

class SessionLimit implements ValidationRule
{
    private $classId;
    /**
     * Run the validation rule.
     */

    public function __construct($classId)
    {
        $this->classId = $classId;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        $sessionMaxNumber = Classroom::find($this->classId)->session_number;
        $currentSessionCount = Session::where('class_id', $this->classId)->count();
        if ($currentSessionCount >= $sessionMaxNumber) {
            $fail('The session limit for this class has been exceeded.');
        }
    }
}
