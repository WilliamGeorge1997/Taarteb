<?php

namespace Modules\Expense\App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Expense\App\Models\StudentExpense;
use Modules\Expense\Service\StudentExpenseService;
use Modules\Expense\App\resources\ExpenseStudentResource;

class ExpenseStudentAdminController extends Controller
{
    public function __construct(private StudentExpenseService $studentExpenseService)
    {
        $this->middleware('auth:user');
        $this->middleware('role:School Manager|Financial Director');
    }

    public function index(Request $request){
        $data = $request->all();
        $relations = ['expense.grade.gradeCategory', 'student', 'expense.exceptions'];
        $studentExpenses = $this->studentExpenseService->findAll($data, $relations);
        return returnMessage(true, 'Student expenses fetched successfully', ExpenseStudentResource::collection($studentExpenses));
    }

    public function update(Request $request, StudentExpense $studentExpense){
        $request->validate([
            'status' => 'required|in:paid,rejected',
            'rejected_reason' => 'required_if:status,rejected|string|max:255'
        ]);
        $studentExpense->update([
            'status' => $request->status,
            'rejected_reason' => $request->rejected_reason
        ]);
        return returnMessage(true, 'Student expense status updated successfully');
    }
}
