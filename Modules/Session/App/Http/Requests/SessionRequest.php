<?php

namespace Modules\Session\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Session\App\Rules\SessionLimit;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SessionRequest extends FormRequest
{
    /**
     * Get the credentials for authentication.
     *
     * @return array<string, mixed>
     */
    public function credentials(): array
    {
        $user = auth('user')->user();
        $baseFields = ['day', 'session_number', 'semester', 'year', 'class_id', 'subject_id', 'teacher_id'];

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
                'day' => ['required', 'in:saturday,sunday,monday,tuesday,wednesday,thursday,friday'],
                'session_number' => ['required', 'integer'],
                'semester' => ['required', 'in:first,second'],
                'year' => ['required', 'string'],
                'class_id' => ['required', 'exists:classes,id', new SessionLimit($this->input('class_id'))],
                'subject_id' => ['required', 'exists:subjects,id'],
                'teacher_id' => ['required', 'exists:teacher_profiles,id'],
            ];
            if (auth('user')->user()->hasRole('Super Admin')) {
                $rules['school_id'] = ['required', 'exists:schools,id'];
            }
            return $rules;
        }
        if ($this->isMethod('PUT')) {
            $rules = [
                'day' => ['nullable', 'in:saturday,sunday,monday,tuesday,wednesday,thursday,friday'],
                'session_number' => ['nullable', 'integer'],
                'semester' => ['nullable', 'in:first,second'],
                'year' => ['nullable', 'string'],
                'class_id' => ['nullable', 'exists:classes,id'],
                'subject_id' => ['nullable', 'exists:subjects,id'],
                'teacher_id' => ['nullable', 'exists:teacher_profiles,id'],
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
            'day' => 'Day',
            'session_number' => 'Session Number',
            'semester' => 'Semester',
            'year' => 'Year',
            'class_id' => 'Class',
            'subject_id' => 'Subject',
            'teacher_id' => 'Teacher',
            'school_id' => 'School',
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
                return $admin->school_id == $this->session->school_id;
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
