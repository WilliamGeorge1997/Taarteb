<?php

namespace Modules\Salary\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Salary\DTO\SalaryDto;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Modules\Salary\App\Models\Salary;
use Modules\Salary\Service\SalaryService;
use Modules\Salary\App\resources\SalaryResource;
use Modules\Salary\App\Http\Requests\SalaryRequest;

class SalaryController extends Controller
{
    private $salaryService;

    public function __construct(SalaryService $salaryService)
    {
        $this->middleware('auth:user');
        $this->middleware('role:School Manager|Financial Director|Salaries Employee');
        $this->salaryService = $salaryService;
    }

    public function mySalaries(Request $request)
    {
        $relations = ['employee'];
        $salaries = $this->salaryService->findMySalaries($request->all(), $relations);
        return returnMessage(true, 'Salaries fetched successfully', SalaryResource::collection($salaries)->response()->getData(true));
    }

    public function store(SalaryRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = (new SalaryDto($request, true))->dataFromRequest();
            $salary = $this->salaryService->create($data);
            DB::commit();
            return returnMessage(true, 'Salary created successfully', $salary);
        } catch (\Exception $e) {
            DB::rollBack();
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function update(SalaryRequest $request, Salary $salary)
    {
        try {
            DB::beginTransaction();
            $data = (new SalaryDto($request))->dataFromRequest();
            $salary = $this->salaryService->update($salary, $data);
            DB::commit();
            return returnMessage(true, 'Salary updated successfully', $salary);
        } catch (\Exception $e) {
            DB::rollBack();
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }
}
