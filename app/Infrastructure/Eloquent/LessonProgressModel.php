<?php

namespace App\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Model;

class LessonProgressModel extends Model
{
    protected $table = 'lesson_progress';

    protected $fillable = ['lesson_id', 'student_id', 'completed_at'];

    protected $casts = [
        'completed_at' => 'immutable_datetime',
    ];
}