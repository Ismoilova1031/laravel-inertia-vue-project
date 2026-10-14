<?php

namespace App\Models;

use App\Enums\NotificationType;
use App\Infrastructure\Eloquent\CourseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'student_id',
        'course_id',
        'lesson_id',
        'type',
        'title',
        'description',
        'is_read',
        'read_at',
        'email_sent_at',
        'notified_at',
    ];

    protected $casts = [
        'type' => NotificationType::class,
        'read_at' => 'datetime',
        'email_sent_at' => 'datetime',
        'notified_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(CourseModel::class);
    }

    public function Lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
}
