<?php

namespace Modules\Session\Service;

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
}
