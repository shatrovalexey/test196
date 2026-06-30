<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Поле имени обязательно для заполнения.',
            'email.required' => 'Поле электронной почты обязательно для заполнения.',
            'email.email' => 'Пожалуйста, введите корректный адрес электронной почты.',
            'email.unique' => 'Этот адрес электронной почты уже зарегистрирован.',
            'password.required' => 'Поле пароля обязательно для заполнения.',
            'password.confirmed' => 'Подтверждение пароля не совпадает с паролем.',
        ];
    }
}