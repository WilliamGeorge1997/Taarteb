<?php

use Modules\Common\App\Models\History;
use Modules\Session\App\Models\Session;



function saveHistory($session_id, $attendance, $teacherTakenAttendance)
{
    $session = Session::findOrFail($session_id);
    History::create([
        'day' => $session->day,
        'session_number' => $session->session_number,
        'semester' => $session->semester,
        'year' => $session->year,
        'teacher_id' => $session->teacher_id,
        'attendance_taken_by' => $teacherTakenAttendance,
        'student_id' => $attendance['student_id'],
        'subject_id' => $session->subject_id,
        'session_id' => $session->id,
        'class_id' => $session->class_id,
        'school_id' => $session->school_id,
        'is_present' => $attendance['is_present'],
    ]);

}


