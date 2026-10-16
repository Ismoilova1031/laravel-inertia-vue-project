<?php

namespace App\Domain\Assessment\Exception;

final class TaskLockedException extends AssessmentRuleViolation
{
    public static function create(): self
    {
        return new self('Topshiriqlar kelgan, shuning uchun turi va savollarini o\'zgartirib yoki o\'chirib bo\'lmaydi. Faqat muddat va fayl kengaytmalarini o\'zgartirish mumkin.');
    }
}