<?php

namespace Modules\Salary\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Salary\Service\SalaryService;

class SalaryAdminController extends Controller
{
    private $salaryService;

    public function __construct(SalaryService $salaryService)
    {
        $this->middleware('auth:user');
        $this->middleware('role:Super Admin|School Manager|Financial Director');
        $this->salaryService = $salaryService;
    }

    public function index(Request $request)
    {
        $relations = ['employee', 'createdBy', 'school'];
        $salaries = $this->salaryService->findAll($request->all(), $relations);
        return returnMessage(true, 'Salaries fetched successfully', $salaries);
    }


}
