<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JwtRegisterRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'يرجى ادخال الاسم',
            'name.string' => 'يرجى ادخال الاسم بشكل صحيح',
            'name.max' => 'يرجى ادخال الاسم بشكل صحيح',
            'email.required' => 'يرجى ادخال البريد الالكتروني',
            'email.string' => 'يرجى ادخال البريد الالكتروني بشكل صحيح',
            'email.email' => 'يرجى ادخال البريد الالكتروني بشكل صحيح',
            'email.unique' => 'البريد الالكتروني مستخدم مسبقاً',
            'email.max' => 'يرجى ادخال البريد الالكتروني بشكل صحيح',
            'password.required' => 'يرجى ادخال كلمة المرور',
            'password.min' => 'كلمة المرور يجب أن تكون على الأقل 6 أحرف',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
        ];
    }
}
