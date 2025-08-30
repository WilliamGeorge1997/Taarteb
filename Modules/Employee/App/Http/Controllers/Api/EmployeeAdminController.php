<?php

namespace Modules\Employee\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
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
        $this->middleware('role:Super Admin|School Manager')->only('index');
        $this->middleware('role:School Manager')->except('index');
        $this->employeeService = $employeeService;
    }
    public function index(Request $request)
    {
        $data = $request->all();
        $employees = $this->employeeService->findAll($data, []);
        return returnMessage(true, 'Employees', $employees);
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

    public function employeeRoles()
    {
        $roles = ['Financial Director', 'Sales Employee', 'Purchasing Employee', 'Salaries Employee', 'Maintenance Employee'];
        $employeeRoles = Role::select('id', 'name')->whereIn('name', $roles)->orderBy('id')->get();
        return returnMessage(true, 'Employee Roles', $employeeRoles);
    }
}
