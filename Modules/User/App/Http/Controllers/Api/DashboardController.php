<?php

namespace Modules\User\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\School\App\Models\School;
use Modules\Class\App\Models\Classroom;
use Modules\Student\App\Models\Student;
use Modules\Teacher\App\Models\TeacherProfile;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user,teacher');
    }

    public function index()
    {
        $data = [];
        if (auth('user')->user()) {
            if (auth('user')->user()->hasRole('Super Admin')) {
                $data['schools'] = School::count();
            }
            $data['teachers'] = TeacherProfile::available()->count();
            $data['students'] = Student::available()->count();
            $data['classes'] = Classroom::available()->count();
        }
        return returnMessage(true, 'Dashboard Data Fetched Successfully', $data);
    }
}
