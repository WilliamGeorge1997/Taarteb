<?php

namespace Modules\Grade\App\Http\Controllers\Api;

use Modules\Grade\App\Models\Grade;
use App\Http\Controllers\Controller;
use Modules\Grade\App\resources\GradeResource;


class GradeController extends Controller
{
    public function index()
    {
        return returnMessage(true, 'Grades fetched successfully', GradeResource::collection(Grade::with('gradeCategory:id,name')->get())->response()->getData(true));
    }
    
}
