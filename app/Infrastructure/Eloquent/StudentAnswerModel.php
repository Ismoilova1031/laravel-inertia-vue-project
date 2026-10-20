<?php

namespace App\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Model;

class StudentAnswerModel extends Model
{
    protected $table = 'student_answers';

    protected $fillable = [
        'task_submission_id', 'question_id', 'selected_option_ids',
        'answer', 'score', 'teacher_comment',
    ];

    protected $casts = ['selected_option_ids' => 'array'];
}