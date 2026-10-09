<?php

namespace tests\Unit\Domain\Student;

use App\Domain\Student\EmailAddress;
use App\Domain\Student\HashedPassword;
use App\Domain\Student\PersonName;
use App\Domain\Student\Student;
use App\Domain\Student\Username;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class StudentTest extends TestCase
{
    public function test_email_is_normalized(): void
    {
        $email = new EmailAddress('  Ali@Example.COM ');

        $this->assertSame('ali@example.com', $email->value());
    }

    public function test_invalid_email_is_rejected(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new EmailAddress('not-an-email');
    }

    public function test_username_too_short_is_rejected(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Username('ab');
    }

    public function test_username_with_invalid_chars_is_rejected(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Username('ali valiyev!');
    }

    public function test_empty_name_is_rejected(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new PersonName('Ali', '   ');
    }

    public function test_new_student_has_no_id_until_saved(): void
    {
        $student = Student::register(
            new PersonName('Ali', 'Valiyev'),
            new EmailAddress('ali@example.com'),
            new Username('ali_v'),
            new HashedPassword('$2y$hash'),
        );

        $this->assertNull($student->id());
        $this->assertSame('Ali Valiyev', $student->name()->fullName());
    }

    public function test_student_can_change_email(): void
    {
        $student = Student::register(
            new PersonName('Ali', 'Valiyev'),
            new EmailAddress('ali@example.com'),
            new Username('ali_v'),
            new HashedPassword('$2y$hash'),
        );

        $student->changeEmail(new EmailAddress('new@example.com'));

        $this->assertSame('new@example.com', $student->email()->value());
    }
}
