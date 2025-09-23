<?php

namespace Modules\Expense\App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Common\Helpers\UploadHelper;
use Modules\Expense\DTO\StudentExpenseDto;
use Modules\Expense\App\Models\StudentExpense;
use Modules\Expense\Service\StudentExpenseService;
use Modules\Expense\App\resources\RequiredExpensesResource;
use Modules\Expense\App\Http\Requests\ExpenseStudentRequest;

class ExpenseStudentController extends Controller
{
    use UploadHelper;
    public function __construct(private StudentExpenseService $studentExpenseService)
    {
        $this->middleware('auth:user');
        $this->middleware('role:Student');
    }

    public function requiredExpenses()
    {
        $expenses = $this->studentExpenseService->findRequiredExpenses();
        return returnMessage(true, 'Required expenses fetched successfully', RequiredExpensesResource::collection($expenses));

    }
    public function index(Request $request)
    {
        $data = $request->all();
        $relations = ['expense.grade.gradeCategory'];
        $studentExpenses = $this->studentExpenseService->findBy('student_id', auth('user')->user()->student->id, $data, $relations);
        return returnMessage(true, 'Student expenses fetched successfully', $studentExpenses);
    }
    public function store(ExpenseStudentRequest $request)
    {
        try {
            $data = (new StudentExpenseDto($request))->dataFromRequest();
            $studentExpense = $this->studentExpenseService->create($data);
            return returnMessage(true, 'Student expense created successfully', $studentExpense);
        } catch (\Exception $e) {
            return returnMessage(false, 'Student expense creation failed', $e->getMessage());
        }
    }

    public function update(Request $request, StudentExpense $studentExpense)
    {
        $request->validate([
            'receipt' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:1024'],
            'payment_method'=>['nullable','sometimes','in:1,2,3']
        ]);
        $this->studentExpenseService->update($studentExpense);
        return returnMessage(true, 'Student expense updated successfully');
    }
}
