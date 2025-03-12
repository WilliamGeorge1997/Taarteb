<?php

namespace Modules\Session\App\Rules;

use Closure;
use Modules\Class\App\Models\Classroom;
use Modules\Session\App\Models\Session;
use Illuminate\Contracts\Validation\ValidationRule;

class SessionLimit implements ValidationRule
{
    private $classId;
    private $semester;
    private $year;
    /**
     * Run the validation rule.
     */

    public function __construct($classId, $semester, $year)
    {
        $this->classId = $classId;
        $this->semester = $semester;
        $this->year = $year;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $classroom = Classroom::find($this->classId);
        $sessionMaxNumber = $classroom->session_number;

        $currentSessionCount = Session::where('class_id', $this->classId)
            ->where('semester', $this->semester)
            ->where('year', $this->year)
            ->count();

        if ($currentSessionCount >= $sessionMaxNumber) {
                $fail("The session limit ({$sessionMaxNumber}) for this class in {$this->semester} semester {$this->year} has been exceeded.");
        }
    }
}
