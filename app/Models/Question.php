<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\QuestionType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Task;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\QuestionOption;
use App\Models\StudentAnswer;

class Question extends Model
{
    protected $fillable = [
        'task_id',
        'question',
        'question_type',
        'points',
        'sort_order',
        'correct_answer',
    ];

    protected $casts = [
        'question_type' => QuestionType::class,
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(QuestionOption::class);
    }

    public function studentAnswers(): HasMany
    {
        return $this->hasMany(StudentAnswer::class);
    }
}
