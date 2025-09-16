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
            'exceptions' => ['required', 'array'],
            'exceptions.*.student_id' => ['required', 'exists:students,id,school_id,' . auth('user')->user()->school_id],
            'exceptions.*.exception_price' => ['required', 'numeric', 'min:0'],
        ];

    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'exceptions' => 'Exceptions',
            'exceptions.*.student_id' => 'Student ID',
            'exceptions.*.exception_price' => 'Exception Price',
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
        $validator->after(function ($validator) {
            $expense = $this->route('expense');
            $studentIds = collect($this->input('exceptions', []))->pluck('student_id');

            $existingExceptions = DB::table('expense_student_exceptions')
                ->where('expense_id', $expense->id)
                ->whereIn('student_id', $studentIds)
                ->pluck('student_id');

            if ($existingExceptions->isNotEmpty()) {
                $validator->errors()->add(
                    'exceptions',
                    'Students with IDs [' . $existingExceptions->implode(', ') . '] already have exceptions for this expense'
                );
            }
        });
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
