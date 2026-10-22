<?php

namespace App\Infrastructure\Repository;

use App\Domain\Student\EmailAddress;
use App\Domain\Student\Exception\StudentNotFoundException;
use App\Domain\Student\HashedPassword;
use App\Domain\Student\NewStudent;
use App\Domain\Student\PersonName;
use App\Domain\Student\Student;
use App\Domain\Student\StudentId;
use App\Domain\Student\StudentRepositoryInterface;
use App\Domain\Student\Username;
use App\Infrastructure\Eloquent\StudentModel;
use Illuminate\Support\Collection;

final class EloquentStudentRepository implements StudentRepositoryInterface
{
    public function findById(StudentId $id): ?Student
    {
        $model = StudentModel::find($id->value());

        return $model ? $this->toEntity($model) : null;
    }

    public function create(NewStudent $new): Student
    {
        return $this->toEntity(StudentModel::create([
            'name' => $new->name->name(),
            'surname' => $new->name->surname(),
            'email' => $new->email->value(),
            'username' => $new->username->value(),
            'password' => $new->password->value(),
        ]));
    }

    public function save(Student $student): void
    {
        $model = StudentModel::find($student->id()->value())
            ?? throw StudentNotFoundException::withId($student->id());

        $model->update([
            'name' => $student->name()->name(),
            'surname' => $student->name()->surname(),
            'email' => $student->email()->value(),
            'username' => $student->username()->value(),
            'password' => $student->password()->value(),
        ]);
    }

    public function delete(StudentId $id): void
    {
        StudentModel::whereKey($id->value())->delete();
    }

    public function existsByUsername(Username $username, ?StudentId $exceptId = null): bool
    {
        return StudentModel::where('username', $username->value())
            ->when($exceptId, fn ($q) => $q->whereKeyNot($exceptId->value()))
            ->exists();
    }

    public function existsByEmail(EmailAddress $email, ?StudentId $exceptId = null): bool
    {
        return StudentModel::where('email', $email->value())
            ->when($exceptId, fn ($q) => $q->whereKeyNot($exceptId->value()))
            ->exists();
    }

    private function toEntity(StudentModel $model): Student
    {
        return Student::reconstitute(
            new StudentId((int) $model->id),
            new PersonName($model->name, $model->surname),
            new EmailAddress($model->email),
            new Username($model->username),
            new HashedPassword($model->password),
        );
    }

    public function all(): Collection
    {
        dd(StudentModel::query()->orderBy('surname')->orderBy('name')->get()->map(fn (StudentModel $m) => $this->toEntity($m)));

        return StudentModel::query()->orderBy('surname')->orderBy('name')->get()
            ->map(fn (StudentModel $m) => $this->toEntity($m));
    }

    public function findByIds(array $ids): Collection
    {
        return StudentModel::query()
            ->whereIn('id', array_map(fn (StudentId $id) => $id->value(), $ids))
            ->get()
            ->map(fn (StudentModel $m) => $this->toEntity($m));
    }

    public function findExcludingIds(array $ids): Collection
    {
        return StudentModel::query()
            ->whereNotIn('id', array_map(fn (StudentId $id) => $id->value(), $ids))
            ->orderBy('surname')->orderBy('name')->get()
            ->map(fn (StudentModel $m) => $this->toEntity($m));
    }
}
