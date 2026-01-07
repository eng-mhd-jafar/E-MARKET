<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JwtLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'يرجى ادخال البريد الالكتروني',
            'email.email' => 'يرجى ادخال البريد الالكتروني بشكل صحيح',
            'password.required' => 'يرجى ادخال كلمة المرور',
        ];
    }
}
