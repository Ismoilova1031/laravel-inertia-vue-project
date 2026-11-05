<?php

namespace App\Domain\CourseInterest;

enum InterestType: string
{
    case LIKE = 'like';
    case PLANNED = 'planned';
}
