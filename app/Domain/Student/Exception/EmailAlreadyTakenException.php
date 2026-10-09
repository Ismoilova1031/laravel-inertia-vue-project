<?php

namespace App\Domain\Student\Exception;

use App\Domain\Student\EmailAddress;
use DomainException;

final class EmailAlreadyTakenException extends DomainException
{
    public static function withEmail(EmailAddress $email): self
    {
        return new self("Email band: {$email->value()}");
    }
}