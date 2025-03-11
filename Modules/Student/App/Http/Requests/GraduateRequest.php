<?php

namespace Modules\Student\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Student\App\Rules\StudentBelongToSchool;

class GraduateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            "students" => ['required', 'array'],
            'students.*' => ['required', 'exists:students,id', new StudentBelongToSchool($this->input('students.*'), auth('user')->user()->school_id)]
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
