<?php

namespace Modules\Expense\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;

class ExpenseExceptionRequest extends FormRequest
{
    public function rules(): array
    {
        return [

            'student_ids' => ['required', 'array'],
            'student_ids.*' => ['required', 'exists:students,id,school_id,' . auth('user')->user()->school_id, 'distinct'],
            'exception_price' => ['required', 'numeric', 'min:0'],
        ];

    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'student_ids' => 'Student IDs',
            'student_ids.*' => 'Student ID',
            'exception_price' => 'Exception Price',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $expense = $this->route('expense');
        if ($expense) {
            $user = auth('user')->user();
            if ($expense->school_id !== $user->school_id) {
                throw new HttpResponseException(
                    returnMessage(
                        false,
                        'You are not authorized to update this expense',
                        null,
                        'unauthorized'
                    )
                );
            }
        }


        return true;
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        if (!in_array('update', request()->segments())) {
            $validator->after(function ($validator) {
                $expense = $this->route('expense');
                $studentIds = $this->input('student_ids');

                $existingExceptions = DB::table('expense_student_exceptions')
                    ->where('expense_id', $expense->id)
                    ->whereIn('student_id', $studentIds)
                    ->pluck('student_id');

                if (!empty($existingExceptions)) {
                    $studentNames = DB::table('students')
                        ->whereIn('id', $existingExceptions)
                        ->get()
                        ->pluck('name');
                }
                if ($existingExceptions->isNotEmpty()) {
                    $validator->errors()->add(
                        'student_ids',
                        'الطلاب بالأسماء ' . $studentNames->implode(', ') . ' لديهم بالفعل استثناءات لهذه النفقات'
                    );
                }
            });
        }
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            returnValidationMessage(
                false,
                trans('validation.rules_failed'),
                $validator->errors()->messages(),
                'unprocessable_entity'
            )
        );
    }
}
