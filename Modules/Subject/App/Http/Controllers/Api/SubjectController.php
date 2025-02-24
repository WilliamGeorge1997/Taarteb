<?php

namespace Modules\Subject\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Subject\App\Models\Subject;


class SubjectController extends Controller
{
    public function index()
    {
        return returnMessage(true, 'Subjects fetched successfully', Subject::all());
    }
}
