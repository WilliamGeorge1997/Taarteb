<?php

namespace Modules\Session\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Session\App\Rules\SessionLimit;
use Illuminate\Contracts\Validation\Validator;
use Modules\Class\App\Rules\ClassBelongToSchool;
use Modules\Subject\App\Rules\SubjectBelongToSchool;
use Modules\Teacher\App\Rules\TeacherBelongToSchool;
use Illuminate\Http\Exceptions\HttpResponseException;

class SessionRequest extends FormRequest
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
                'day' => ['required', 'in:saturday,sunday,monday,tuesday,wednesday,thursday,friday'],
                'session_number' => ['required', 'integer', 'max:15'],
                'semester' => ['required', 'in:first,second', 'exists:subjects,semester,id,' . $this->input('subject_id')],
                'year' => ['required', 'string'],
                'class_id' => auth('user')->user()->hasRole('School Manager') ?
                    ['required', 'exists:classes,id', new ClassBelongToSchool($this->input('class_id'), auth('user')->user()->school_id), new SessionLimit($this->input('class_id'), $this->input('semester'), $this->input('year'), $this->input('day'))] :
                    ['required', 'exists:classes,id', new ClassBelongToSchool($this->input('class_id'), $this->input('school_id')), new SessionLimit($this->input('class_id'), $this->input('semester'), $this->input('year'), $this->input('day'))],
                'subject_id' => auth('user')->user()->hasRole('School Manager') ?
                    ['required', 'exists:subjects,id', new SubjectBelongToSchool($this->input('subject_id'), auth('user')->user()->school_id)] :
                    ['required', 'exists:subjects,id', new SubjectBelongToSchool($this->input('subject_id'), $this->input('school_id'))],
                'teacher_id' => auth('user')->user()->hasRole('School Manager') ?
                    ['required', 'exists:teacher_profiles,id', new TeacherBelongToSchool($this->input('teacher_id'), auth('user')->user()->school_id)] :
                    ['required', 'exists:teacher_profiles,id', new TeacherBelongToSchool($this->input('teacher_id'), $this->input('school_id'))],
                'is_final' => ['required', 'boolean'],
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
                'day' => ['nullable', 'in:saturday,sunday,monday,tuesday,wednesday,thursday,friday'],
                'session_number' => ['nullable', 'integer', 'max:15'],
                'semester' => ['nullable', 'in:first,second', 'exists:subjects,semester,id,' . $this->input('subject_id')],
                'year' => ['nullable', 'string'],
                'class_id' => auth('user')->user()->hasRole('School Manager') ?
                    ['nullable', 'exists:classes,id', new ClassBelongToSchool($this->input('class_id'), auth('user')->user()->school_id), new SessionLimit($this->input('class_id'), $this->input('semester'), $this->input('year'), $this->input('day'))] :
                    ['nullable', 'exists:classes,id', new ClassBelongToSchool($this->input('class_id'), $this->input('school_id')), new SessionLimit($this->input('class_id'), $this->input('semester'), $this->input('year'), $this->input('day'))],
                'subject_id' => auth('user')->user()->hasRole('School Manager') ?
                    ['nullable', 'exists:subjects,id', new SubjectBelongToSchool($this->input('subject_id'), auth('user')->user()->school_id)] :
                    ['nullable', 'exists:subjects,id', new SubjectBelongToSchool($this->input('subject_id'), $this->input('school_id'))],
                'teacher_id' => auth('user')->user()->hasRole('School Manager') ?
                    ['nullable', 'exists:teacher_profiles,id', new TeacherBelongToSchool($this->input('teacher_id'), auth('user')->user()->school_id)] :
                    ['nullable', 'exists:teacher_profiles,id', new TeacherBelongToSchool($this->input('teacher_id'), $this->input('school_id'))],
                'is_final' => ['nullable', 'boolean'],
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
            'day' => 'Day',
            'session_number' => 'Session Number',
            'semester' => 'Semester',
            'year' => 'Year',
            'class_id' => 'Class',
            'subject_id' => 'Subject',
            'teacher_id' => 'Teacher',
            'school_id' => 'School',
            'is_final' => 'Is Final',
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
