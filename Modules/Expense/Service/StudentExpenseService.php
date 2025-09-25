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
        $existingStudentExpense = StudentExpense::where('student_id', $data['student_id'])
            ->where('expense_id', $data['expense_id'])
            ->first();

        if (request()->hasFile('receipt')) {
            if ($existingStudentExpense && $existingStudentExpense->receipt) {
                File::delete(public_path('uploads/student/expense/receipt/' . $this->getImageName('student/expense/receipt', $existingStudentExpense->receipt)));
            }
            $data['receipt'] = $this->upload(request()->file('receipt'), 'student/expense/receipt');
        }

        $studentExpense = StudentExpense::updateOrCreate(
            [
                'student_id' => $data['student_id'],
                'expense_id' => $data['expense_id']
            ],
            $data
        );
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
                'gradeCategory',
                'grade',
                'school',
                'requests' => function ($query) use ($student) {
                    $query->where('student_id', $student->id);
                },
            ])->latest()
            ->get();
        return $expenses;
    }
}
