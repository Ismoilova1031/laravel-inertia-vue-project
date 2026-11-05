<?php

namespace App\Domain\Notification;

enum NotificationType: int
{
    case CERTIFICATE_ISSUED = 1;
    case FILE_ACCEPTED = 2;
    case FILE_REJECTED = 3;
    case ANSWER_GRADED = 4;
}
