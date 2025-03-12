<?php

namespace Modules\Student\App\Http\Requests;

use Modules\Student\App\Rules\MaxStudents;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Modules\Subject\App\Rules\GradeBelongToSchool;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\Class\App\Rules\ClassBelongToSchool;

class StudentRequest extends FormRequest
{

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
                'email' => ['required', 'email', 'unique:students,email', 'unique:students,parent_email', 'different:parent_email'],
                'identity_number' => ['required', 'string', 'unique:students,identity_number'],
                'gender' => ['required', 'in:m,f'],
                'grade_id' => auth('user')->user()->hasRole('School Manager') ?
                    ['required', 'exists:grades,id', new GradeBelongToSchool($this->input('grade_id'), auth('user')->user()->school_id)] :
                    ['required', 'exists:grades,id', new GradeBelongToSchool($this->input('grade_id'), $this->input('school_id'))],
                'class_id' => auth('user')->user()->hasRole('School Manager') ?
                    ['required', 'exists:classes,id', new ClassBelongToSchool($this->input('grade_id'), auth('user')->user()->school_id), new MaxStudents($this->input('class_id'))] :
                    ['required', 'exists:classes,id', new ClassBelongToSchool($this->input('grade_id'), $this->input('school_id')), new MaxStudents($this->input('class_id'))],
                'parent_email' => ['required', 'email', 'unique:students,parent_email', 'unique:students,email', 'different:email'],
            ];
            if (auth('user')->user()->hasRole('Super Admin')) {
                $rules['school_id'] = ['required', 'exists:schools,id'];
            } else {
                $rules['school_id'] = ['prohibited'];
            }
            return $rules;
        }
        if ($this->isMethod('PUT')) {
            $rules = [
                'name' => ['nullable', 'string', 'max:255'],
                'email' => [
                    'nullable',
                    'email',
                    'unique:students,email,' . $this->student->id,
                    'unique:students,parent_email,' . $this->student->id,
                    'not_in:' . ($this->input('parent_email') ?? $this->student->parent_email),
                ],
                'identity_number' => [
                    'nullable',
                    'string',
                    'unique:students,identity_number,' . $this->student->id . ',id'
                ],
                'gender' => ['nullable', 'in:m,f'],
                'grade_id' => auth('user')->user()->hasRole('School Manager') ?
                    ['nullable', 'exists:grades,id', new GradeBelongToSchool($this->input('grade_id'), auth('user')->user()->school_id)] :
                    ['nullable', 'exists:grades,id', new GradeBelongToSchool($this->input('grade_id'), $this->input('school_id'))],
                'class_id' => auth('user')->user()->hasRole('School Manager') ?
                    ['nullable', 'exists:classes,id', new ClassBelongToSchool($this->input('grade_id'), auth('user')->user()->school_id), new MaxStudents($this->input('class_id'))] :
                    ['nullable', 'exists:classes,id', new ClassBelongToSchool($this->input('grade_id'), $this->input('school_id')), new MaxStudents($this->input('class_id'))],
                'parent_email' => [
                    'nullable',
                    'email',
                    'unique:students,parent_email,' . $this->student->id,
                    'unique:students,email,' . $this->student->id,
                    'not_in:' . ($this->input('email') ?? $this->student->email),
                ],
            ];
            if (auth('user')->user()->hasRole('Super Admin')) {
                $rules['school_id'] = ['nullable', 'exists:schools,id'];
            } else {
                $rules['school_id'] = ['prohibited'];
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
            'identity_number' => 'Identity Number',
            'gender' => 'Gender',
            'class_id' => 'Class',
            'grade_id' => 'Grade',
            'school_id' => 'School',
            'parent_email' => 'Parent Email',
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
                // Check if the student's school ID matches the authenticated user's school ID
                return $admin->school_id == $this->student->school_id;
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
