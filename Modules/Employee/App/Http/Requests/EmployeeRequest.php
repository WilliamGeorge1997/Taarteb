<?php

namespace Modules\Employee\App\Http\Requests;

use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class EmployeeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $roles_ids = Role::whereIn('name', ['Financial Director', 'Sales Employee', 'Purchasing Employee', 'Salaries Employee', 'Maintenance Employee'])->pluck('id');
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'sometimes|nullable|string|max:255|unique:users,phone',
            'password' => 'required|string|min:6',
            'image' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
            'role_id' => 'required|exists:roles,id|in:' . $roles_ids->implode(','),
        ];
        if (auth('user')->user()->hasRole('Super Admin'))
            $rules['school_id'] = 'required|exists:schools,id';

        return $rules;
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
            'image' => 'Image',
            'role_id' => 'Role',
            'school_id' => 'School',
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
