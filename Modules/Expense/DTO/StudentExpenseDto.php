<?php


namespace Modules\Expense\DTO;

use Modules\Expense\App\Models\Expense;


class StudentExpenseDto
{
    public $student_id;
    public $expense_id;
    public $amount;
    public $date;

    public function __construct($request)
    {
        $this->student_id = auth('user')->user()->student->id;
        $this->expense_id = $request->get('expense_id');
        $this->amount = $this->getAmount($this->expense_id);
        $this->date = now()->toDateString();
    }

    private function getAmount($expense_id)
    {
        $expense = Expense::findOrFail($expense_id)->with([
            'exceptions' => function ($query) {
                $query->where('student_id', $this->student_id);
            }
        ])->first();
        if ($expense->exceptions->count() > 0) {
            return $expense->exceptions->first()->pivot->exception_price;
        }
        return $expense->price;
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
        return $data;
    }
}
