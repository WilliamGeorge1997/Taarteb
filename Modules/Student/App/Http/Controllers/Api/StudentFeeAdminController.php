<?php

namespace Modules\Student\App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Student\App\Models\StudentFee;
use Modules\Student\Service\StudentFeeService;

class StudentFeeAdminController extends Controller
{
    private $studentFeeService;
    public function __construct(StudentFeeService $studentFeeService)
    {
        $this->middleware('auth:user');
        $this->middleware('role:School Manager');
        $this->studentFeeService = $studentFeeService;
    }

    public function index(Request $request)
    {
        $relations = ['student'];
        $studentFees = $this->studentFeeService->findBySchoolStudents($request->all(), $relations);
        return returnMessage(true, 'Student Fees Fetched Successfully', $studentFees);
    }

    public function update(StudentFee $studentFee, Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'payment_status' => 'sometimes|in:paid,pending,failed',
                'status' => 'sometimes|in:accepted,rejected,pending',
            ]);
            $data = $request->validated();
            $studentFee->update($data);
            if ($data['payment_status'] == 'paid' || $data['status'] == 'accepted')
                $studentFee->student->update(['is_fee_paid' => 1]);
            DB::commit();
            return returnMessage(true, 'Student Fee Updated Successfully', $studentFee->fresh()->load('student'));
        } catch (Exception $e) {
            DB::rollBack();
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

}
