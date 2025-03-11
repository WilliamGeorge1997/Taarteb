<?php

namespace Modules\Common\Service;

use Modules\Common\App\Models\History;

class HistoryService
{
    public function findAll($data = [])
    {
        $histories = History::query()->with(['student', 'teacher', 'grade', 'class', 'subject', 'school'])->available()->get();

        return getCaseCollection($histories, $data);
    }
}
