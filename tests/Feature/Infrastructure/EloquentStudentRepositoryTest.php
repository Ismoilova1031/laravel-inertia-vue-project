<?php

namespace Tests\Feature\Infrastructure;

use App\Domain\Student\EmailAddress;
use App\Domain\Student\HashedPassword;
use App\Domain\Student\PersonName;
use App\Domain\Student\Student;
use App\Domain\Student\StudentRepositoryInterface;
use App\Domain\Student\Username;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class EloquentStudentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private StudentRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->app->make(StudentRepositoryInterface::class);
    }

    private function newStudent(string $username = 'ali_v', string $email = 'ali@example.com'): Student
    {
        return Student::register(
            new PersonName('Ali', 'Valiyev'),
            new EmailAddress($email),
            new Username($username),
            new HashedPassword('$2y$hash'),
        );
    }

    public function test_save_assigns_id_to_new_student(): void
    {
        $saved = $this->repository->save($this->newStudent());

        $this->assertNotNull($saved->id());
        $this->assertDatabaseHas('students', ['username' => 'ali_v', 'surname' => 'Valiyev']);
    }

    public function test_find_by_id_returns_entity(): void
    {
        $saved = $this->repository->save($this->newStudent());

        $found = $this->repository->findById($saved->id());

        $this->assertSame('Ali Valiyev', $found->name()->fullName());
    }

    public function test_save_updates_existing_student(): void
    {
        $saved = $this->repository->save($this->newStudent());
        $saved->rename(new PersonName('Vali', 'Karimov'));

        $this->repository->save($saved);

        $this->assertDatabaseHas('students', ['name' => 'Vali', 'surname' => 'Karimov']);
        $this->assertDatabaseCount('students', 1);
    }

    public function test_exists_by_username_ignores_given_id(): void
    {
        $saved = $this->repository->save($this->newStudent());

        $this->assertTrue($this->repository->existsByUsername(new Username('ali_v')));
        $this->assertFalse($this->repository->existsByUsername(new Username('ali_v'), $saved->id()));
    }

    public function test_delete_removes_student(): void
    {
        $saved = $this->repository->save($this->newStudent());

        $this->repository->delete($saved->id());

        $this->assertNull($this->repository->findById($saved->id()));
    }
}