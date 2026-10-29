<?php

namespace App\Domain\Review\Exception;

use DomainException;

final class ReviewNotAllowedException extends DomainException
{
    public static function create(): self
    {
        return new self('Kursni baholash uchun uni to\'liq tugatish kerak.');
    }
}