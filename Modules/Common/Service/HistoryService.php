<?php

namespace Modules\Common\Service;

use Modules\Common\App\Models\History;

class HistoryService
{
    public function findAll($data = [])
    {
        $histories = History::query()->available()->with(['student', 'teacher', 'grade', 'class', 'subject', 'school', 'attendanceTakenBy']);

        return getCaseCollection($histories, $data);
    }
}
