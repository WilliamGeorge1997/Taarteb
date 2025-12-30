<?php

namespace Modules\Student\App\Http\Requests;

use Modules\Student\App\Rules\MaxStudents;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Modules\Class\App\Rules\ClassBelongToSchool;
use Modules\Subject\App\Rules\GradeBelongToSchool;
use Illuminate\Http\Exceptions\HttpResponseException;

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
                'email' => ['required', 'email', 'unique:students,email', 'unique:students,parent_email', 'different:parent_email', 'unique:users,email'],
                'identity_number' => ['required', 'string', 'unique:students,identity_number'],
                'gender' => ['required', 'in:m,f'],
                'grade_id' => auth('user')->user()->hasRole('School Manager') ?
                    ['required', 'exists:grades,id', new GradeBelongToSchool(auth('user')->user()->school_id)] :
                    ['required', 'exists:grades,id', new GradeBelongToSchool($this->input('school_id'))],
                'class_id' => auth('user')->user()->hasRole('School Manager') ?
                    ['required', 'exists:classes,id', new ClassBelongToSchool($this->input('class_id'), auth('user')->user()->school_id), new MaxStudents($this->input('class_id'))] :
                    ['required', 'exists:classes,id', new ClassBelongToSchool($this->input('class_id'), $this->input('school_id')), new MaxStudents($this->input('class_id'))],
                'parent_email' => ['nullable', 'email', 'unique:students,parent_email', 'unique:students,email', 'different:email'],
                'parent_phone' => ['required', 'string'],
                'is_fee_paid' => ['required', 'in:0,1'],
                'address' => ['nullable', 'string'],
                'password' => ['required', 'string', 'min:6'],
                'password_confirmation' => ['required', 'string', 'min:6', 'same:password'],
                'application_form' => ['required', 'file', 'mimes:pdf', 'max:1024'],
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
                    'required',
                    'string',
                    'unique:students,identity_number,' . $this->student->id,
                ],
                'gender' => ['nullable', 'in:m,f'],
                'grade_id' => auth('user')->user()->hasRole('School Manager') ?
                    ['nullable', 'exists:grades,id', new GradeBelongToSchool(auth('user')->user()->school_id)] :
                    ['nullable', 'exists:grades,id', new GradeBelongToSchool($this->input('school_id'))],
                'class_id' => auth('user')->user()->hasRole('School Manager') ?
                    ['nullable', 'exists:classes,id', new ClassBelongToSchool($this->input('class_id'), auth('user')->user()->school_id), new MaxStudents($this->input('class_id'))] :
                    ['nullable', 'exists:classes,id', new ClassBelongToSchool($this->input('class_id'), $this->input('school_id')), new MaxStudents($this->input('class_id'))],
                'parent_email' => [
                    'nullable',
                    'email',
                    'unique:students,parent_email,' . $this->student->id,
                    'unique:students,email,' . $this->student->id,
                    'not_in:' . ($this->input('email') ?? $this->student->email),
                ],
                'parent_phone' => ['nullable', 'string'],
                'is_fee_paid' => ['nullable', 'in:0,1'],
                'address' => ['nullable', 'string'],
                'password' => ['nullable', 'string', 'min:6'],
                'password_confirmation' => ['nullable', 'string', 'min:6', 'same:password'],
                'application_form' => ['nullable', 'file', 'mimes:pdf', 'max:1024'],
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
            'grade_id' => 'Grade',
            'school_id' => 'School',
            'phone' => 'Phone Number',
            'parent_email' => 'Parent Email',
            'parent_phone' => 'Parent Phone',
            'password' => 'Password',
            'password_confirmation' => 'Password Confirmation',
            'application_form' => 'Application Form',
            'address' => 'Address',
            'state_id' => 'State',
            'branch_id' => 'Branch',
            'name_en' => 'Name in English',
            'birth_date' => 'Birth Date',
            'education_level' => 'Education Level',
            'has_learning_difficulties' => 'Has Learning Difficulties',
            'educational_system' => 'Educational System',
            'behavioral_data' => 'Behavioral Data',
            'pronunciation' => 'Pronunciation',
            'chronic_diseases' => 'Chronic Diseases',
            'food_allergies' => 'Food Allergies',
            'other_notes' => 'Other Notes',
            'transport' => 'Transport',
            'street_number' => 'Street Number',
            'house_number' => 'House Number',
            'nearest_landmark' => 'Nearest Landmark',
            'home_location_url' => 'Home Location URL',
            'siblings_count' => 'Siblings Count',
            'parent_identity_card_image' => 'Parent Identity Card Image',
            'student_residence_card_image' => 'Student Residence Card Image',
            'image' => 'Image',
            'student_passport_image' => 'Student Passport Image',
            'student_birth_certificate_image' => 'Student Birth Certificate Image',
            'student_health_card_image' => 'Student Health Card Image',
            'home_map_image' => 'Home Map Image',
            'parent_name' => 'Parent Name',
            'parent_nationality' => 'Parent Nationality',
            'parent_identity_number' => 'Parent Identity Number',
            'parent_job' => 'Parent Job',
            'parent_job_address' => 'Parent Job Address',
            'parent_education_level' => 'Parent Education Level',
            'mother_name' => 'Mother Name',
            'mother_nationality' => 'Mother Nationality',
            'mother_identity_number' => 'Mother Identity Number',
            'mother_job' => 'Mother Job',
            'mother_job_address' => 'Mother Job Address',
            'mother_education_level' => 'Mother Education Level',
            'mother_phone' => 'Mother Phone',
            'parents_status' => 'Parents Status',
            'relative_name' => 'Relative Name',
            'relative_relation' => 'Relative Relation',
            'relative_phone' => 'Relative Phone',
            'distinguished_skills' => 'Distinguished Skills',
            'has_previous_education' => 'Has Previous Education',
            'previous_school_data' => 'Previous School Data',
            'can_distinguish_letters_randomly' => 'Can Distinguish Letters Randomly',
            'reads_short_words' => 'Reads Short Words',
            'reads_short_sentences' => 'Reads Short Sentences',
            'memorizes_quran_surahs' => 'Memorizes Quran Surahs',
            'memorizes_quran_from' => 'Memorizes Quran From',
            'memorizes_quran_to' => 'Memorizes Quran To',
            'additional_educational_notes' => 'Additional Educational Notes',
            'sibling_order' => 'Sibling Order',
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
