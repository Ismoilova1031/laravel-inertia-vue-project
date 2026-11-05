<?php

namespace App\Domain\Student\Exception;

use App\Domain\Student\VO\Username;
use DomainException;

final class UsernameAlreadyTakenException extends DomainException
{
    public static function withUsername(Username $username): self
    {
        return new self("Username band: {$username->value()}");
    }
}
