<?php

namespace App\Domain\Enrollment;

enum EnrollmentStatus: int
{
    case PASSIVE = 0;
    case ACTIVE = 1;
}
