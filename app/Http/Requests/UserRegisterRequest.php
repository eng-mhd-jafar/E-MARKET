<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
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
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone_number' => ['required', 'string', 'max:255'],
        ];


    }
    public function messages()
    {
        return [
            'name.required' => 'يرجى ادخال الاسم',
            'name.string' => 'يرجى ادخال الاسم بشكل صحيح',
            'name.max' => 'يرجى ادخال الاسم بشكل صحيح',
            'email.required' => 'يرجى ادخال البريد الالكتروني',
            'email.string' => 'يرجى ادخال البريد الالكتروني بشكل صحيح',
            'email.email' => 'يرجى ادخال البريد الالكتروني بشكل صحيح',
            'email.max' => 'يرجى ادخال البريد الالكتروني بشكل صحيح',
            'password.required' => 'يرجى ادخال كلمة المرور',
            'phone_number.required' => 'يرجى ادخال رقم الجوال',
            'phone_number.string' => 'يرجى ادخال رقم الجوال بشكل صحيح',
            'phone_number.max' => 'يرجى ادخال رقم الجوال بشكل صحيح',
        ];
    }
}
