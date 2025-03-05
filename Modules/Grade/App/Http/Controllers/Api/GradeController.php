<?php

namespace Modules\Grade\App\Http\Controllers\Api;

use Modules\Grade\App\Models\Grade;
use App\Http\Controllers\Controller;
use Modules\Grade\App\resources\GradeResource;


class GradeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
        $this->middleware('role:Super Admin|School Manager');
        $this->middleware('permission:Index-grade|Create-grade|Edit-grade|Delete-grade', ['only' => ['index', 'store']]);
        $this->middleware('permission:Create-grade', ['only' => ['store']]);
        $this->middleware('permission:Edit-grade', ['only' => ['update', 'activate']]);
        $this->middleware('permission:Delete-grade', ['only' => ['destroy']]);
    }
    public function index()
    {
        return returnMessage(true, 'Grades fetched successfully', GradeResource::collection(Grade::available()->with('gradeCategory:id,name')->get())->response()->getData(true));
    }

}
