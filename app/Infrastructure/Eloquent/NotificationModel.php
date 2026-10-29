<?php

namespace App\Infrastructure\Eloquent;


use App\Domain\Notification\NotificationType;
use Illuminate\Database\Eloquent\Model;

class NotificationModel extends Model
{
    protected $table = 'notifications';

    protected $fillable = ['student_id', 'course_id', 'type', 'title', 'description', 'read_at'];

    protected $casts = [
        'type' => NotificationType::class, 'read_at' => 'immutable_datetime'
    ];
}