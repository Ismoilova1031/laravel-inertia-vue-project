<?php

namespace App\Infrastructure\Eloquent;

use App\Domain\Assessment\QuestionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestionModel extends Model
{
    protected $table = 'questions';

    protected $fillable = ['task_id', 'question', 'question_type', 'points', 'sort_order', 'correct_answer'];

    protected $casts = ['question_type' => QuestionType::class];

    public function task(): BelongsTo
    {
        return $this->belongsTo(TaskModel::class, 'task_id');
    }

    public function options(): HasMany
    {
        return $this->hasMany(QuestionOptionModel::class, 'question_id')->orderBy('id');
    }

    public function studentAnswers(): HasMany
    {
        return $this->hasMany(StudentAnswerModel::class, 'question_id');
    }
}
