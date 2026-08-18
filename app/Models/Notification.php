<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\NotificationType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Student;
use App\Models\Course;
use App\Models\Lesson;

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
        return $this->belongsTo(Course::class);
    }

    public function Lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
}
