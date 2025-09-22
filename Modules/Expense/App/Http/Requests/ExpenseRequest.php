<?php

namespace Modules\Expense\App\Http\Requests;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ExpenseRequest extends FormRequest
{
    private $expense;
    public function prepareForValidation()
    {
        $this->expense = $this->route()->hasParameter('expense') ? $this->route('expense') : null;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'grade_category_id' => ['required', 'exists:grade_categories,id'],
            'grade_id' => ['required', 'exists:grades,id'],
            'price' => ['required', 'numeric', 'min:0'],
        ];

    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'grade_category_id' => 'Grade Category ID',
            'grade_id' => 'Grade ID',
            'price' => 'Price',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->expense) {
            $user = auth('user')->user();
            if ($this->expense->user_id !== $user->id) {
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
