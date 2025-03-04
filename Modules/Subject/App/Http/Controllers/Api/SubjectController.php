<?php

namespace Modules\Subject\App\Http\Controllers\Api;

use Modules\Grade\App\Models\Grade;
use App\Http\Controllers\Controller;
use Modules\Subject\App\Models\Subject;
use Modules\Subject\App\resources\SubjectResource;


class SubjectController extends Controller
{
    public function subjectsByGradeId(Grade $grade)
    {
        return returnMessage(true, 'Subjects fetched successfully', SubjectResource::collection($grade->subjects()->get())->response()->getData(true));
    }
}
