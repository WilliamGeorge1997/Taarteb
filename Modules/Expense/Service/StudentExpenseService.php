<?php

namespace Modules\Expense\Service;

use Modules\Expense\App\Models\Expense;
use Modules\Expense\App\Models\StudentExpense;


class StudentExpenseService
{
    function findAll($data = [], $relations = [])
    {
        $studentExpenses = StudentExpense::query()->with($relations)->available()->latest();
        return getCaseCollection($studentExpenses, $data);
    }

    function findById($id)
    {
        return StudentExpense::findOrFail($id);
    }

    function findBy($key, $value, $relations = [])
    {
        return StudentExpense::where($key, $value)->with($relations)->get();
    }

    function create($data)
    {
        $studentExpense = StudentExpense::create($data);
        return $studentExpense;
    }

    function update($studentExpense, $data)
    {
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
        $expensePayRequestIds = StudentExpense::query()
            ->where('student_id', $student->id)
            ->get()->pluck('expense_id');

        $expenses = Expense::query()
            ->whereNotIn('id', $expensePayRequestIds)
            ->where('grade_id', $student->grade_id)
            ->where('grade_category_id', $student->grade->gradeCategory->id)
            ->where('school_id', $student->school_id)
            ->with([
                'exceptions' => function ($query) use ($student) {
                    $query->where('student_id', $student->id);
                },
                'gradeCategory',
                'grade',
                'school'
            ])->latest()
            ->get();
        return $expenses;
    }
}
