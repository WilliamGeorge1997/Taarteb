<?php

namespace Modules\Employee\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Modules\User\DTO\EmployeeUserDto;
use Modules\User\Service\UserService;
use Modules\Employee\Service\EmployeeService;
use Modules\Employee\App\resources\EmployeeResource;
use Modules\Employee\App\Http\Requests\EmployeeRequest;

class EmployeeAdminController extends Controller
{
    private $employeeService;
    public function __construct(EmployeeService $employeeService)
    {
        $this->middleware('auth:user')->except('employeeRoles');
        $this->middleware('role:Super Admin|School Manager|Financial Director|Salaries Employee');
        $this->employeeService = $employeeService;
    }
    public function index(Request $request)
    {
        $data = $request->all();
        $relations = ['roles:id,name', 'school'];
        $employees = $this->employeeService->findAll($data, $relations);
        return returnMessage(true, 'Employees', EmployeeResource::collection($employees)->response()->getData(true));
    }

    public function store(EmployeeRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = (new EmployeeUserDto($request))->dataFromRequest();
            $employee = (new UserService())->saveEmployeeUser($data);
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
