<?php

namespace Modules\Session\Service;

use Modules\Student\App\Models\Student;
use Modules\Session\App\Models\Attendance;

class AttendanceService
{
    function findAll($data = [])
    {
        $attendances = Attendance::query()
            ->orderByDesc('created_at');
        return getCaseCollection($attendances, $data);
    }

    function findById($id)
    {
        $attendance = Attendance::available()->findOrFail($id);
        return $attendance;
    }

    function findBy($key, $value)
    {
        $attendance = Attendance::available()->where($key, $value)->get();
        return $attendance;
    }

    function create($data)
    {
        $attendance = Attendance::create($data);
        saveHistory($data);
        return $attendance;
    }

    function update($attendance, $data)
    {
        $attendance->update($data);
        return $attendance;
    }

    function delete($attendance)
    {
        $attendance->delete();
    }

    function getStudentsForAttendance($data = [])
    {
        $students = Student::query()
            ->where('class_id', $data['class_id'])
            ->whereHas('class.sessions', function ($q) use ($data) {
                $q->where('class_id', $data['class_id'])
                    ->where('year', $data['year'])
                    ->where('day', $data['day'])
                    ->where('semester', $data['semester'])
                    ->where('session_number', $data['session_number']);
            })
            ->whereDoesntHave('attendance', function ($q) use ($data) {
                $q->whereHas('session', function ($q) use ($data) {
                    $q->where('class_id', $data['class_id'])
                        ->where('day', $data['day'])
                        ->where('semester', $data['semester'])
                        ->where('session_number', $data['session_number'])
                        ->where('year', $data['year']);
                })
                ->whereDate('created_at', now()->toDateString());
            });
        return getCaseCollection($students, $data);
    }
}
