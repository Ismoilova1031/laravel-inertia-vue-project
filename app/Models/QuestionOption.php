<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Question;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\StudentAnswer;

class QuestionOption extends Model
{
    protected $fillable = [
        'question_id',
        'option',
        'is_correct',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function studentAnswers(): HasMany
    {
        return $this->hasMany(StudentAnswer::class);
    }
}
