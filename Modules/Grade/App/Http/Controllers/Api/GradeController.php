<?php

namespace Modules\Grade\App\Http\Controllers\Api;

use Modules\Grade\App\Models\Grade;
use App\Http\Controllers\Controller;


class GradeController extends Controller
{
    public function index()
    {
        return returnMessage(true, 'Grades fetched successfully', Grade::all());
    }
}
