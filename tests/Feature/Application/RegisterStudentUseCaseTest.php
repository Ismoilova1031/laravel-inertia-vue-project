<?php
// tests/Feature/Application/RegisterStudentUseCaseTest.php
declare(strict_types=1);

namespace Tests\Feature\Application;

use App\Application\Student\RegisterStudent\RegisterStudentCommand;
use App\Application\Student\RegisterStudent\RegisterStudentUseCase;
use App\Domain\Student\Exception\EmailAlreadyTakenException;
use App\Domain\Student\Exception\UsernameAlreadyTakenException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

final class RegisterStudentUseCaseTest extends TestCase
{
    use RefreshDatabase;

    private function command(string $username = 'ali_v', string $email = 'ali@example.com'): RegisterStudentCommand
    {
        return new RegisterStudentCommand('Ali', 'Valiyev', $email, $username, 'secret123');
    }

    public function test_registers_student_and_hashes_password(): void
    {
        $id = $this->app->make(RegisterStudentUseCase::class)->execute($this->command());

        $this->assertDatabaseHas('students', ['id' => $id->value(), 'username' => 'ali_v']);

        $stored = DB::table('students')->where('id', $id->value())->value('password');
        $this->assertNotSame('secret123', $stored);
        $this->assertTrue(Hash::check('secret123', $stored));
    }

    public function test_rejects_duplicate_username(): void
    {
        $useCase = $this->app->make(RegisterStudentUseCase::class);
        $useCase->execute($this->command());

        $this->expectException(UsernameAlreadyTakenException::class);

        $useCase->execute($this->command(email: 'other@example.com'));
    }

    public function test_rejects_duplicate_email(): void
    {
        $useCase = $this->app->make(RegisterStudentUseCase::class);
        $useCase->execute($this->command());

        $this->expectException(EmailAlreadyTakenException::class);

        $useCase->execute($this->command(username: 'other_user'));
    }
}