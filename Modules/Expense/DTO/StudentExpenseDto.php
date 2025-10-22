<?php


namespace Modules\Expense\DTO;

use Modules\Expense\App\Models\Expense;
use Modules\Expense\App\Models\StudentExpense;


class StudentExpenseDto
{
    public $student_id;
    public $expense_id;
    public $amount;
    public $date;
    public $payment_method;

    public function __construct($request)
    {
        $this->student_id = auth('user')->user()->student->id;
        $this->expense_id = $request->get('expense_id');
        $this->amount = $this->getAmount($this->expense_id);
        $this->date = now()->toDateString();
        $this->payment_method = $request->get('payment_method');
    }

    private function getAmount($expense_id)
    {
        $student = auth('user')->user()->student;

        $expense = Expense::with([
            'exceptions' => function ($query) {
                $query->where('student_id', $this->student_id);
            }
        ])->findOrFail($expense_id);

        $basePrice = $expense->exceptions->count() > 0
            ? $expense->exceptions->first()->pivot->exception_price
            : $expense->price;

        $hasNeverPaid = !$student->expense_registration_fee_deducted;
        $isFirstExpense = !StudentExpense::where('student_id', $this->student_id)
            ->where('expense_id', $expense_id)
            ->exists();

        if ($hasNeverPaid && $isFirstExpense) {
            $basePrice = $basePrice - 10;
        }

        return $basePrice;
    }

    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);
        if ($this->expense_id == null)
            unset($data['expense_id']);
        if ($this->amount == null)
            unset($data['amount']);
        if ($this->date == null)
            unset($data['date']);
        if ($this->payment_method == null)
            unset($data['payment_method']);
        return $data;
    }
}
