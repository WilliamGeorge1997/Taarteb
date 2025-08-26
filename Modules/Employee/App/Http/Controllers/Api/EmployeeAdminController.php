<?php

namespace Modules\Employee\App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Modules\Employee\DTO\EmployeeDto;
use Modules\Employee\Service\EmployeeService;
use Modules\Employee\App\Http\Requests\EmployeeRequest;

class EmployeeAdminController extends Controller
{
    private $employeeService;
    public function __construct(EmployeeService $employeeService)
    {
        $this->middleware('auth:user');
        $this->middleware('role:School Manager');
        $this->employeeService = $employeeService;
    }

    public function store(EmployeeRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = (new EmployeeDto($request))->dataFromRequest();
            $employee = $this->employeeService->create($data);
            DB::commit();
            return returnMessage(true, 'Employee Created Successfully', $employee);
        } catch (\Exception $e) {
            DB::rollBack();
            return returnMessage(false, $e->getMessage(), null, 'error');
        }
    }
}
