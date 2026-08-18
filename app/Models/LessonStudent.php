<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Lesson;
use App\Models\Student;

class LessonStudent extends Model
{
    protected $fillable = [
        'lesson_id',
        'student_id',
        'is_completed',
        'completion_percent',
        'score',
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
