<?php

namespace App\Domain\Progress\Exception;

use DomainException;

final class LessonNotCompletableManuallyException extends DomainException
{
    public function create(): self
    {
        return new self('Topshiriqli dars qo\'lda belgilanishi mumkin emas. Topshiriq natijalariga qarab dars avtomatik ravishda yakunlanadi.');
    }
}