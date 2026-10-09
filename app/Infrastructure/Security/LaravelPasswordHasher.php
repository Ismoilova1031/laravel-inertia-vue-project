<?php

namespace App\Infrastructure\Security;

use App\Domain\Student\HashedPassword;
use App\Domain\Student\PasswordHasherInterface;
use Illuminate\Contracts\Hashing\Hasher;

final class LaravelPasswordHasher implements PasswordHasherInterface
{
    public function __construct(private readonly Hasher $hasher) {}

    public function hash(string $plainPassword): HashedPassword
    {
        return new HashedPassword($this->hasher->make($plainPassword));
    }
}