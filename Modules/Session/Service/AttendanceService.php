<?php

namespace Modules\Session\Service;

use Modules\Session\App\Models\Session;
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
        foreach ($data['attendance'] as $attendance) {
            $existingAttendance = Attendance::where('session_id', $data['session_id'])
                ->where('student_id', $attendance['student_id'])
                ->whereDate('created_at', now()->toDateString())
                ->first();

            if (!$existingAttendance) {
                Attendance::create([
                    'session_id' => $data['session_id'],
                    'student_id' => $attendance['student_id'],
                    'is_present' => $attendance['is_present'],
                ]);
            }
            saveHistory($data['session_id'], $attendance);
        }
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

    function getSessionWithStudents($data = [])
    {
        $session = Session::query()
            ->available()
            ->where('class_id', $data['class_id'])
            ->where('year', $data['year'])
            ->where('day', $data['day'])
            ->where('semester', $data['semester'])
            ->where('session_number', $data['session_number'])
            ->with([
                'class.students' => function ($query) use ($data) {
                    $query->where('is_graduated', 0)
                        ->withCount([
                        'attendance as is_attend' => function ($q) use ($data) {
                            $q->whereHas('session', function ($sq) use ($data) {
                                $sq->where('class_id', $data['class_id'])
                                    ->where('day', $data['day'])
                                    ->where('semester', $data['semester'])
                                    ->where('session_number', $data['session_number'])
                                    ->where('year', $data['year']);
                            })
                                ->whereDate('created_at', now()->toDateString());
                        }
                    ]);
                }
            ])
            ->first();

        // $students = Student::query()
        //     ->where('class_id', $data['class_id'])
        //     ->whereHas('class.sessions', function ($q) use ($data) {
        //         $q->where('class_id', $data['class_id'])
        //             ->where('year', $data['year'])
        //             ->where('day', $data['day'])
        //             ->where('semester', $data['semester'])
        //             ->where('session_number', $data['session_number']);
        //     })
        //     ->with('class.sessions', function ($q) use ($data) {
        //         $q->where('class_id', $data['class_id'])
        //             ->where('day', $data['day'])
        //             ->where('semester', $data['semester'])
        //             ->where('session_number', $data['session_number'])
        //             ->where('year', $data['year']);
        //     })
        //     ->whereDoesntHave('attendance', function ($q) use ($data) {
        //         $q->whereHas('session', function ($q) use ($data) {
        //             $q->where('class_id', $data['class_id'])
        //                 ->where('day', $data['day'])
        //                 ->where('semester', $data['semester'])
        //                 ->where('session_number', $data['session_number'])
        //                 ->where('year', $data['year']);
        //         })
        //         ->whereDate('created_at', now()->toDateString());
        //     });
        return $session;
    }
}
