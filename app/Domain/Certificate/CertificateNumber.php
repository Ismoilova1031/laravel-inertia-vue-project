<?php

namespace App\Domain\Certificate;

use InvalidArgumentException;

final class CertificateNumber
{
    public function __construct(private readonly string $value)
    {
        if (!preg_match('/^SP-\d{4}-[A-F0-9]{8}$/', $value)) {
            throw new InvalidArgumentException('Sertifikat raqami noto\'g\'ri.');
        }
    }

    public static function generate(\DateTimeImmutable $now): self
    {
        return new self('SP-' . $now->format('Y') . '-' . strtoupper(bin2hex(random_bytes(4))));
    }

    public function value(): string { return $this->value; }
}