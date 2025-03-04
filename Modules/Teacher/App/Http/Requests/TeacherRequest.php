<?php

namespace Modules\Teacher\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TeacherRequest extends FormRequest
{
    /**
     * Get the credentials for authentication.
     *
     * @return array<string, mixed>
     */
    public function credentials(): array
    {
        $user = auth('user')->user();
        $baseFields = ['name', 'email', 'phone', 'password', 'gender', 'image', 'subject_id', 'grade_id'];

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
        if ($this->isMethod('POST')) {
            $rules = [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'unique:users,email'],
                'phone' => ['required', 'string', 'unique:users,phone'],
                'password' => ['required', 'string', 'min:6'],
                'gender' => ['required', 'in:m,f'],
                'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:1024'],
                'grade_id' => ['required', 'exists:grades,id'],
                'subject_id' => ['required', 'exists:subjects,id'],
            ];
            if (auth('user')->user()->hasRole('Super Admin')) {
                $rules['school_id'] = ['required', 'exists:schools,id'];
            }
            return $rules;
        }
        if ($this->isMethod('PUT')) {
            $rules = [
                'name' => ['nullable', 'string', 'max:255'],
                'email' => ['nullable', 'email', 'unique:users,email,' . $this->teacher->teacher->id],
                'phone' => ['nullable', 'string', 'unique:users,phone,' . $this->teacher->teacher->id],
                'password' => ['nullable', 'string', 'min:6'],
                'gender' => ['nullable', 'in:m,f'],
                'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:1024'],
                'subject_id' => ['nullable', 'exists:subjects,id'],
                'grade_id' => ['nullable', 'exists:grades,id'],
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
            'email' => 'Email Address',
            'phone' => 'Phone Number',
            'password' => 'Password',
            'gender' => 'Gender',
            'image' => 'Image',
            'subject_id' => 'Subject',
            'grade_id' => 'Grade',
            'school_id' => 'School',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if($this->isMethod('PUT')){
            $admin = auth('user')->user();
            if ($admin->hasRole('School Manager')) {
                // Check if the teacher's school ID matches the authenticated user's school ID
                return $admin->school_id == $this->teacherProfile->teacher->school_id;
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
