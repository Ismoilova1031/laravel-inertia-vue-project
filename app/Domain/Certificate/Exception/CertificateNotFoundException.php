<?php

namespace App\Domain\Certificate\Exception;

use DomainException;

final class CertificateNotFoundException extends DomainException
{
    public static function create(): self
    {
        return new self('Sertifikat topilmadi.');
    }
}