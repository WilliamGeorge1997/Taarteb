<?php

namespace Modules\Expense\App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Student\App\Models\Student;
use Modules\School\Service\SchoolService;
use Modules\Common\Helpers\WhatsAppService;
use Modules\Expense\App\Models\StudentExpense;
use Modules\Expense\Service\StudentExpenseService;
use Modules\Notification\Service\NotificationService;
use Modules\Expense\App\resources\ExpenseStudentResource;
use Modules\Expense\App\Http\Requests\ExpenseStudentAdminRequest;

class ExpenseStudentAdminController extends Controller
{
    public function __construct(private StudentExpenseService $studentExpenseService)
    {
        $this->middleware('auth:user');
        $this->middleware('role:School Manager|Financial Director');
    }

    public function index(Request $request)
    {
        $data = $request->all();
        $relations = ['expense.grade.gradeCategory', 'student', 'expense.exceptions'];
        $studentExpenses = $this->studentExpenseService->findAll($data, $relations);
        return returnMessage(true, 'Student expenses fetched successfully', ExpenseStudentResource::collection($studentExpenses));
    }

    public function update(ExpenseStudentAdminRequest $request, StudentExpense $studentExpense)
    {
        $studentExpense = $this->studentExpenseService->updateStatus($request->all(),$studentExpense);
        $this->sendNotificationToStudent($studentExpense);
        $this->parentNotificationWhatsApp($studentExpense);
        return returnMessage(true, 'Student expense status updated successfully', $studentExpense);
    }

    public function sendNotificationToStudent($studentExpense)
    {
        if ($studentExpense->status === 'accepted') {
            $data = [
                'title' => 'تم دفع النفقات',
                'description' => 'تم دفع نفقاتك بنجاح.',
            ];
        } elseif ($studentExpense->status === 'rejected') {
            $data = [
                'title' => 'تم رفض النفقات',
                'description' => 'تم رفض طلب دفع النفقات الخاص بك. السبب: ' . ($studentExpense->rejected_reason ?? 'لم يتم تحديد السبب'),
            ];
        }
        (new NotificationService())->sendNotificationToUser($data, $studentExpense->student->user_id, 'expense');
    }

    private function parentNotificationWhatsApp($studentExpense)
    {
        $schoolSettings = (new SchoolService())->findById($studentExpense->school_id)->settings;
        $student = Student::find($studentExpense->student_id);
        if ($student->parent_phone) {
            $whatsAppService = new WhatsAppService($schoolSettings);
            $message = $studentExpense->status == 'accepted'
                ? 'تم دفع نفقاتك بنجاح'
                : 'تم رفض طلب دفع النفقات الخاص بك و السبب: ' . ($studentExpense->rejected_reason ?? 'لم يتم تحديد السبب');
            $whatsAppService->sendMessage($student->parent_phone, $message);
        }
    }
}
