<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\TaskSubmission;
use App\Models\Question;
use App\Models\QuestionOption;

class StudentAnswer extends Model
{
    protected $fillable = [
        'task_submission_id',
        'question_id',
        'question_option_id',
        'answer',
        'score',
        'teacher_comment',
    ];

    public function taskSubmission(): BelongsTo
    {
        return $this->belongsTo(TaskSubmission::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function questionOption(): BelongsTo
    {
        return $this->belongsTo(QuestionOption::class);
    }
}
