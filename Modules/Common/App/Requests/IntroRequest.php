<?php

namespace Modules\Common\App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class IntroRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        if ($this->isMethod('POST')) {
            return[
                'title_ar' => ['required', 'string', 'max:255'],
                'title_en' => ['required', 'string', 'max:255'],
                'description_ar' => ['required', 'string'],
                'description_en' => ['required', 'string'],
                'image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:1024'],
            ];
        }
        if ($this->isMethod('PUT')) {
            return[
                'title_ar' => ['nullable', 'string', 'max:255'],
                'title_en' => ['nullable', 'string', 'max:255'],
                'description_ar' => ['nullable', 'string'],
                'description_en' => ['nullable', 'string'],
                'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:1024'],
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
            'title_ar' => 'Arabic Title',
            'title_en' => 'English Title',
            'description_ar' => 'Arabic Description',
            'description_en' => 'English Description',
            'image' => 'Image',
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
