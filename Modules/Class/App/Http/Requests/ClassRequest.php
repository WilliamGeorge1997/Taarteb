<?php

namespace Modules\Class\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ClassRequest extends FormRequest
{
    /**
     * Get the credentials for authentication.
     *
     * @return array<string, mixed>
     */
    public function credentials(): array
    {
        return ['title', 'grade_id', 'max_students', 'period_number'];


    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        if ($this->isMethod('POST')) {
            return [
                'title' => ['required', 'string', 'max:255'],
                'grade_id' => ['required', 'exists:grades,id'],
                'max_students' => ['required', 'integer', 'min:1'],
                'period_number' => ['required', 'integer', 'min:1'],
            ];
        }
        if ($this->isMethod('PUT')) {
            return  [
                'title' => ['nullable', 'string', 'max:255'],
                'grade_id' => ['nullable', 'exists:grades,id'],
                'max_students' => ['nullable', 'integer', 'min:1'],
                'period_number' => ['nullable', 'integer', 'min:1'],
            ];
        }
        return [];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'title' => 'Title',
            'grade_id' => 'Grade',
            'max_students' => 'Max Students',
            'period_number' => 'Period Number',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator): void
    {
        $errors = [];
        foreach ($validator->errors()->toArray() as $field => $messages) {
            $errors[$field] = array_map(fn(string $message) => __($message), $messages);
        }

        throw new HttpResponseException(
            returnValidationMessage(
                false,
                trans('validation.rules_failed'),
                $errors,
                'unprocessable_entity'
            )
        );
    }
}
