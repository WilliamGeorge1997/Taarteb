<?php

namespace Modules\School\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SchoolRequest extends FormRequest
{
    /**
     * Get the credentials for authentication.
     *
     * @return array<string, mixed>
     */
    public function credentials(): array
    {
        if($this->isMethod('POST')){
            return $this->only(['name', 'email', 'phone', 'grade', 'manager_name', 'manager_email', 'manager_password']);
        }
        if($this->isMethod('PUT')){
            return $this->only(['name', 'email', 'phone', 'grade']);
        }
        return [];
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
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'unique:schools,email'],
                'phone' => ['required', 'string', 'unique:schools,phone'],
                'grade' => ['required', 'string'],
                'manager_name' => ['required', 'string', 'max:255'],
                'manager_email' => ['required', 'email', 'unique:managers,email'],
                'manager_password' => ['required', 'string', 'min:8'],
            ];
        }
        if ($this->isMethod('PUT')) {
            return [
                'name' => ['nullable', 'string', 'max:255'],
                'email' => ['nullable', 'email', 'unique:schools,email,' . $this->school->id],
                'phone' => ['nullable', 'string', 'unique:schools,phone,' . $this->school->id],
                'grade' => ['nullable', 'string'],
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
            'name' => 'Name',
            'email' => 'Email Address',
            'phone' => 'Phone Number',
            'grade' => 'Grade',
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
