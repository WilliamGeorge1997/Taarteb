<?php

namespace Modules\Student\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Student\Service\StudentService;


class StudentRegisterAdminController extends Controller
{
    private $studentService;
    public function __construct(StudentService $studentService)
    {
        $this->middleware('auth:user');
        $this->middleware('role:School Manager|Financial Director');
        $this->studentService = $studentService;
    }

    public function index()
    {
        $relations = ['grade.gradeCategory', 'class'];
        $students = $this->studentService->findBy('is_fee_paid', 0, $relations);
        return returnMessage(true, 'Students fetched successfully', $students);
    }


    public function markAsPaid($id)
    {
        $student = $this->studentService->findById($id);
        $student->is_fee_paid = 1;
        $student->is_register_fee_accepted = 1;
        $student->save();
        return returnMessage(true, 'Student marked as paid successfully');
    }

    public function rejectRegisterFee($id)
    {
        $student = $this->studentService->findById($id);
        $student->is_register_fee_accepted = 0;
        $student->save();
        return returnMessage(true, 'Student register fee rejected successfully');
    }
}
