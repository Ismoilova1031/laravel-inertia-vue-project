<?php
namespace App\Http\Requests;

use App\Application\Student\UpdateStudent\UpdateStudentCommand;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = (int) $this->route('student');

        return [
            'name'     => ['required', 'string', 'max:255'],
            'surname'  => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('students', 'email')->ignore($id)],
            'username' => ['required', 'regex:/^[a-zA-Z0-9_]{3,30}$/', Rule::unique('students', 'username')->ignore($id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Ism maydoni majburiy.',
            'surname.required'  => 'Familiya maydoni majburiy.',
            'email.required'    => 'Email maydoni majburiy.',
            'email.email'       => 'Email manzili noto‘g‘ri formatda.',
            'email.unique'      => 'Bu email manzili allaqachon ishlatilgan.',
            'username.required' => 'Foydalanuvchi nomi maydoni majburiy.',
            'username.regex'    => 'Foydalanuvchi nomi faqat harflar, raqamlar va pastki chiziqlarni o‘z ichiga olishi mumkin va 3 dan 30 gacha belgidan iborat bo‘lishi kerak.',
            'username.unique'   => 'Bu foydalanuvchi nomi allaqachon ishlatilgan.',
            'password.min'      => 'Parol kamida 8 ta belgidan iborat bo‘lishi kerak.',
            'password.confirmed'=> 'Parol tasdiqlanishi mos kelmayapti.',
        ];
    }
    
    public function toCommand(): UpdateStudentCommand
    {
        $data = $this->validated();

        return new UpdateStudentCommand(
            id: (int) $this->route('student'),
            name: $data['name'],
            surname: $data['surname'],
            email: $data['email'],
            username: $data['username'],
            password: $data['password'] ?? null,
        );
    }
}