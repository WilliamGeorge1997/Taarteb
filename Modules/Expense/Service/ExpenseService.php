<?php

namespace Modules\Expense\Service;

use Modules\Expense\App\Models\Expense;


class ExpenseService
{
    function findAll($data = [], $relations = [])
    {
        $expenses = Expense::query()->with($relations)->available()->latest();
        return getCaseCollection($expenses, $data);
    }

    function findById($id)
    {
        return Expense::findOrFail($id);
    }

    function findBy($key, $value)
    {
        return Expense::where($key, $value)->get();
    }

    function create($data)
    {
        $expense = Expense::create($data);
        return $expense;
    }

    function update($expense, $data)
    {
        $expense->update($data);
        return $expense;
    }

    function delete($expense)
    {
        $expense->delete();
    }

    function findExceptions($expense)
    {
        return $expense->exceptions()->get();
    }

    function saveExceptions($expense, $data)
    {
        $expense->exceptions()->attach($data['student_ids'], ['exception_price' => $data['exception_price']]);
        return $expense->load('exceptions');
    }

    function updateExceptions($expense, $data)
    {
        foreach ($data['student_ids'] as $student_id) {
            $expense->exceptions()->updateExistingPivot(
                $student_id,
                ['exception_price' => $data['exception_price']]
            );
        }
        return $expense->load('exceptions');
    }

    function findStudentExpenses($student)
    {
        return $student->expenses()->get();
    }
}
