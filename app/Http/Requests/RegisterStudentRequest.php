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
