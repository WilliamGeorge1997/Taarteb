<?php

namespace Modules\Expense\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Expense\DTO\ExpenseDto;
use App\Http\Controllers\Controller;
use Modules\Expense\App\Models\Expense;
use Modules\Expense\Service\ExpenseService;
use Modules\Expense\App\Http\Requests\ExpenseRequest;
use Modules\Expense\App\Http\Requests\ExpenseExceptionRequest;

class ExpenseController extends Controller
{
    public function __construct(private ExpenseService $expenseService)
    {
        $this->middleware('auth:user');
        $this->middleware('role:School Manager');
    }

    public function index(Request $request)
    {
        $data = $request->all();
        $relations = ['gradeCategory', 'grade', 'exceptions'];
        $expenses = $this->expenseService->findAll($data, $relations);
        return returnMessage(true, 'Expenses fetched successfully', $expenses);
    }

    public function store(ExpenseRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = (new ExpenseDto($request, true))->dataFromRequest();
            $expense = $this->expenseService->create($data);
            DB::commit();
            return returnMessage(true, 'Expense created successfully', $expense);
        } catch (\Exception $e) {
            DB::rollBack();
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function storeExceptions(ExpenseExceptionRequest $request, Expense $expense)
    {
        try{
            DB::beginTransaction();
            $data = $request->validated();
            $exceptions = $this->expenseService->saveExceptions($expense, $data);
            DB::commit();
            return returnMessage(true, 'Exceptions fetched successfully', $exceptions);
        }catch(\Exception $e) {
            DB::rollBack();
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }
}
