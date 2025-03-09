<?php

namespace Modules\Subject\App\Models\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SubjectRequest extends FormRequest
{
    /**
     * Get the credentials for authentication.
     *
     * @return array<string, mixed>
     */
    public function credentials(): array
    {
        $user = auth('user')->user();
        $baseFields = ['name', 'grade_id'];

        if ($this->isMethod('POST') || $this->isMethod('PUT')) {
            if ($user->hasRole('Super Admin')) {
                $baseFields[] = 'school_id';
            }
            return $this->only($baseFields);
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
        dd($this->all());
        if ($this->isMethod('POST')) {
            $rules = [
                'name' => ['required', 'string', 'max:255'],
                'grade_category_id' => ['required', 'exists:grade_categories,id', new BelongToSchool($this->input('grade_category_id'))],
            ];
            if (auth('user')->user()->hasRole('Super Admin')) {
                $rules['school_id'] = ['required', 'exists:schools,id'];
            }
            return $rules;
        }
        if ($this->isMethod('PUT')) {
            $rules = [
                'name' => ['nullable', 'string', 'max:255'],
                'grade_category_id' => ['nullable', 'exists:grade_categories,id', new BelongToSchool($this->input('grade_category_id'))],
            ];
            if (auth('user')->user()->hasRole('Super Admin')) {
                $rules['school_id'] = ['nullable', 'exists:schools,id'];
            }
            return $rules;
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
            'school_id' => 'School',
            'grade_category_id' => 'Grade Category',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->isMethod('PUT')) {
            $admin = auth('user')->user();
            if ($admin->hasRole('School Manager')) {
                // Check if the grades's school ID matches the authenticated user's school ID
                return $admin->school_id == $this->grade->school_id;
            }
        }
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
