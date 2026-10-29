<?php

namespace App\Domain\Certificate\Exception;

use DomainException;

final class CertificateRuleException extends DomainException
{
    public static function because(string $reason): self
    {
        return new self($reason);
    }
}