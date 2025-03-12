<?php

namespace Modules\Student\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Modules\Student\App\Rules\StudentBelongToSchool;
use Illuminate\Http\Exceptions\HttpResponseException;

class GraduateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            "students" => ['required', 'array'],
            'students.*' => ['required', 'exists:students,id', new StudentBelongToSchool($this->input('students.*'), auth('user')->user()->school_id)]
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'students' => 'Students',
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
