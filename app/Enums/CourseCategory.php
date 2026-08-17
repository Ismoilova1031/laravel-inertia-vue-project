<?php

namespace App\Enums;

enum CourseCategory: int
{
    case Programming = 0;
    case Design = 1;
    case Marketing = 2;
    case Business = 3;
    case Photography = 4;
    case Music = 5;
    case Language = 6;
    case HealthAndFitness = 7;
    case PersonalDevelopment = 8;
    case FinanceAndAccounting = 9;
    case None = 10;

    public function label(): string
    {
        return match ($this) {
            self::Programming => 'Programming',
            self::Design => 'Design',
            self::Marketing => 'Marketing',
            self::Business => 'Business',
            self::Photography => 'Photography',
            self::Music => 'Music',
            self::Language => 'Language',
            self::HealthAndFitness => 'Health and Fitness',
            self::PersonalDevelopment => 'Personal Development',
            self::FinanceAndAccounting => 'Finance and Accounting',
            self::None => 'None',
        };
    }
    public static function labels(): array
    {
       return collect(self::cases())->map(fn($case) => ['title' => $case->label(), 'value' => $case->value])->toArray();
    }
}