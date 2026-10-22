<?php

namespace App\Domain\Assessment\Submission;

enum SubmissionStatus: int
{
    case PENDING = 0;
    case ACCEPTED = 1;
    case REJECTED = 2;
}
