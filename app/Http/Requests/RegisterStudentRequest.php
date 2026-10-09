<?php

namespace App\Http\Requests;

use App\Application\Student\RegisterStudent\RegisterStudentCommand;
use Illuminate\Foundation\Http\FormRequest;

final class RegisterStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:students,email'],
            'username' => ['required', 'regex:/^[a-zA-Z0-9_]{3,30}$/', 'unique:students,username'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'Ism kiritilishi shart.',
            'surname.required'   => 'Familiya kiritilishi shart.',
            'email.required'     => 'Email kiritilishi shart.',
            'email.email'        => 'Email manzil noto\'g\'ri.',
            'email.unique'       => 'Bu email band.',
            'username.required'  => 'Username kiritilishi shart.',
            'username.regex'     => 'Username 3-30 belgi bo\'lib, faqat a-z, 0-9 va _ dan iborat bo\'lishi kerak.',
            'username.unique'    => 'Bu username band.',
            'password.required'  => 'Parol kiritilishi shart.',
            'password.min'       => 'Parol kamida 8 belgidan iborat bo\'lishi kerak.',
            'password.confirmed' => 'Parollar mos kelmadi.',
        ];
    }

    public function toCommand(): RegisterStudentCommand
    {
        $data = $this->validated();

        return new RegisterStudentCommand(
            name: $data['name'],
            surname: $data['surname'],
            email: $data['email'],
            username: $data['username'],
            password: $data['password'],
        );
    }
}
