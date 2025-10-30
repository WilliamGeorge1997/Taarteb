<?php

namespace Modules\Expense\Service;

use Illuminate\Support\Facades\File;
use Modules\Expense\App\Models\Expense;
use Modules\Common\Helpers\UploadHelper;
use Modules\Expense\App\Models\StudentExpense;


class StudentExpenseService
{
    use UploadHelper;
    function findAll($data = [], $relations = [])
    {
        $studentExpenses = StudentExpense::query()
            ->when($data['grade_id'] ?? null, function ($query) use ($data) {
                $query->whereHas('expense', function ($query) use ($data) {
                    $query->where('grade_id', $data['grade_id']);
                });
            })
            ->when($data['grade_category_id'] ?? null, function ($query) use ($data) {
                $query->whereHas('expense', function ($query) use ($data) {
                    $query->where('grade_category_id', $data['grade_category_id']);
                });
            })
            ->available()
            ->with($relations)->latest();
        return getCaseCollection($studentExpenses, $data);
    }

    function findById($id)
    {
        return StudentExpense::findOrFail($id);
    }

    function findBy($key, $value, $data = [], $relations = [])
    {
        $studentExpenses = StudentExpense::where($key, $value)->with($relations)->latest();
        $collection = getCaseCollection($studentExpenses, $data);
        $collection->transform(function ($studentExpense) {
            if ($studentExpense->expense) {
                $studentExpense->expense->year = $studentExpense->expense->created_at->format('Y');
            }
            return $studentExpense;
        });

        return $collection;
    }

    function create($data)
    {
        if (request()->hasFile('receipt')) {
            $data['receipt'] = $this->upload(request()->file('receipt'), 'student/expense/receipt');
        }
        $existingExpenses = StudentExpense::where('expense_id', $data['expense_id'])
            ->where('student_id', $data['student_id'])
            ->get();

        if ($existingExpenses->count() > 0) {
            $totalAmountPaid = $existingExpenses->sum('amount_paid');
            $latestExpense = $existingExpenses->sortByDesc('id')->first();

            $pendingExpense = $existingExpenses->firstWhere('status', 'pending');
            if ($pendingExpense) {
                throw new \Exception('You have a pending expense request, please wait for it to be accepted or rejected');
            }

            $rejectedExpense = $existingExpenses->firstWhere('status', 'rejected');
            if ($rejectedExpense) {
                throw new \Exception('You have a rejected expense request, please update it before creating a new one');
            }

            if ($latestExpense && $latestExpense->payment_status === 'full' && $totalAmountPaid >= ($data['amount'])) {
                throw new \Exception('You have paid the required amount for this expense');
            }
        }
        $studentExpense = StudentExpense::create($data);
        return $studentExpense;
    }

    function update($studentExpense)
    {
        $data = [];
        if (request()->hasFile('receipt')) {
            $data['receipt'] = $this->upload(request()->file('receipt'), 'student/expense/receipt');
            $data['status'] = 'pending';
        }
        if (request()->has('payment_method') && request()->payment_method != null) {
            $data['payment_method'] = request()->payment_method;
        }
        $studentExpense->update($data);
        return $studentExpense;
    }

    function updateStatus($data, $studentExpense)
    {
        $paymentStatus = null;
        if ($data['status'] == 'accepted') {
            $student = $studentExpense->student;

            $allStudentExpenses = StudentExpense::where('expense_id', $studentExpense->expense_id)
                ->where('student_id', $studentExpense->student_id)
                ->where('status', 'accepted')
                ->get();

            $previouslyPaid = $allStudentExpenses->sum('amount_paid');
            $newPayment = $data['amount_paid'];
            $totalAmountPaid = $previouslyPaid + $newPayment;
            $requiredAmount = $studentExpense->amount;

            $remaining = $requiredAmount - $previouslyPaid;

            if ($newPayment > $remaining) {
                throw new \Exception("Payment amount ({$newPayment}) exceeds remaining balance ({$remaining})");
            }

            $paymentStatus = ($totalAmountPaid >= $requiredAmount) ? 'full' : 'partial';
            if (!$student->expense_registration_fee_deducted && $previouslyPaid == 0) {
                $expense = $studentExpense->expense()->with('details')->first();
                $startPaymentDetail = $expense->details->firstWhere('name', 'مقدم الدفع');
                $startPayment = $startPaymentDetail ? $startPaymentDetail->price : 0;
                $student->update(['expense_registration_fee_deducted' => $startPayment]);
            }
        }

        $studentExpense->update([
            'status' => $data['status'],
            'rejected_reason' => @$data['rejected_reason'],
            'amount_paid' => $data['amount_paid'] ?? 0,
            'payment_status' => @$paymentStatus,
        ]);

        return $studentExpense->fresh();
    }

    function delete($studentExpense)
    {
        $studentExpense->delete();
    }

    function findRequiredExpenses()
    {
        $student = auth('user')->user()->student;
        $expenses = Expense::query()
            ->where('grade_id', $student->grade_id)
            ->where('grade_category_id', $student->grade->gradeCategory->id)
            ->where('school_id', $student->school_id)
            ->with([
                'exceptions' => function ($query) use ($student) {
                    $query->where('student_id', $student->id);
                },
                'details',
                'gradeCategory',
                'grade',
                'school',
                'requests' => function ($query) use ($student) {
                    $query->where('student_id', $student->id);
                },
            ])->latest()
            ->get();
        $hasNeverPaid = !$student->expense_registration_fee_deducted;
        if ($expenses->isNotEmpty() && $hasNeverPaid) {
            $firstExpense = $expenses->first();

            $hasException = $firstExpense->exceptions->isNotEmpty();
            $basePrice = $hasException
                ? $firstExpense->exceptions->first()->pivot->exception_price
                : $firstExpense->price;

            $startPaymentDetail = $firstExpense->details->firstWhere('name', 'مقدم الدفع');
            $firstExpense->registration_fee_deduction = $startPaymentDetail ? $startPaymentDetail->price : 0;
        }

        return $expenses;
    }
}
