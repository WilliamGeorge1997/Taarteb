<?php

namespace Modules\Student\App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Student\DTO\StudentFeeDto;
use Modules\Student\App\Models\StudentFee;
use Modules\Student\Service\StudentFeeService;
use Modules\Student\App\Http\Requests\StudentFeeRequest;

class StudentFeeController extends Controller
{
    private $studentFeeService;
    public function __construct(StudentFeeService $studentFeeService)
    {
        $this->middleware('auth:user');
        $this->middleware('role:Student');
        $this->studentFeeService = $studentFeeService;
    }

    public function myFees(Request $request)
    {
        $studentFees = $this->studentFeeService->findByStudent($request->all());
        return returnMessage(true, 'Student Fees Fetched Successfully', $studentFees);
    }

    public function store(StudentFeeRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = (new StudentFeeDto($request))->dataFromRequest();
            $studentFee = $this->studentFeeService->save($data);
            DB::commit();
            return returnMessage(true, 'Student Fee Created Successfully', $studentFee);
        } catch (Exception $e) {
            DB::rollBack();
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }


    public function update(StudentFeeRequest $request, StudentFee $studentFee)
    {
        if ($studentFee->status != 'rejected') {
            return returnMessage(false, 'You cannot update this fee at this moment', null, 'bad_request');
        }
        $studentFee = $this->studentFeeService->update($request->all(), $studentFee);
        return returnMessage(true, 'Student Fee Updated Successfully', $studentFee);
    }

}
