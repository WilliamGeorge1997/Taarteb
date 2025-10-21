<?php

namespace Modules\Student\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Modules\Subject\App\Rules\GradeBelongToSchool;
use Illuminate\Http\Exceptions\HttpResponseException;

class StudentRegisterRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:students,email', 'unique:students,parent_email', 'unique:users,email', 'different:parent_email'],
            'phone' => ['required', 'string', 'unique:users,phone'],
            'identity_number' => ['nullable', 'string'],
            'gender' => ['required', 'in:m,f'],
            'parent_email' => ['nullable', 'email', 'unique:students,parent_email', 'unique:students,email', 'different:email'],
            'parent_phone' => ['required', 'string'],
            'school_id' => ['required', 'exists:schools,id'],
            'grade_id' => ['required', 'exists:grades,id', new GradeBelongToSchool($this->input('school_id'))],
            'password' => ['required', 'string', 'min:6'],
            'password_confirmation' => ['required', 'string', 'min:6', 'same:password'],
            'application_form' => ['nullable', 'file', 'mimes:pdf', 'max:1024'],
            'address' => ['nullable', 'string'],
            'region_id' => ['required', 'exists:regions,id'],
            'branch_id' => ['required', 'exists:branches,id'],
            'name_en' => ['nullable', 'string'],
            'birth_date' => ['nullable', 'date'],
            'education_level' => ['nullable', 'in:excellent,normal,needs_follow_up'],
            'has_learning_difficulties' => ['nullable', 'boolean'],
            'educational_system' => ['nullable', 'in:monolingual,bilingual'],
            'behavioral_data' => ['nullable', 'array'],
            'behavioral_data.*' => ['nullable', 'string', 'in:articulate,sociable,introvert,shy,unresponsive,inflexible,irritable,hyperactive'],
            'pronunciation' => ['nullable', 'in:excellent,good,needs_follow_up'],
            'chronic_diseases' => ['nullable', 'string'],
            'food_allergies' => ['nullable', 'string'],
            'other_notes' => ['nullable', 'string'],
            'transport' => ['nullable', 'in:school_bus,private_bus'],
            'street_number' => ['nullable', 'string'],
            'house_number' => ['nullable', 'string'],
            'nearest_landmark' => ['nullable', 'string'],
            'home_location_url' => ['nullable', 'string', 'url'],
            'siblings_count' => ['nullable', 'string'],
            'parent_identity_card_image' => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:1024'],
            'student_residence_card_image' => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:1024'],
            'image' => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:1024'],
            'student_passport_image' => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:1024'],
            'student_birth_certificate_image' => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:1024'],
            'student_health_card_image' => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:1024'],
            'home_map_image' => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:1024'],
            'parent_name' => ['nullable', 'string'],
            'parent_nationality' => ['nullable', 'string'],
            'parent_identity_number' => ['nullable', 'string'],
            'parent_job' => ['nullable', 'string'],
            'parent_job_address' => ['nullable', 'string'],
            'parent_education_level' => ['nullable', 'string'],
            'mother_name' => ['nullable', 'string'],
            'mother_nationality' => ['nullable', 'string'],
            'mother_identity_number' => ['nullable', 'string'],
            'mother_job' => ['nullable', 'string'],
            'mother_job_address' => ['nullable', 'string'],
            'mother_education_level' => ['nullable', 'string'],
            'mother_phone' => ['nullable', 'string'],
            'parents_status' => ['nullable', 'in:together,separated,widower,widow'],
            'relative_name' => ['nullable', 'string'],
            'relative_relation' => ['nullable', 'string'],
            'relative_phone' => ['nullable', 'string'],
        ];
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
            'region_id' => 'Region',
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
