<?php

namespace App\Infrastructure\Eloquent;

use App\Models\StudentAnswer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestionOptionModel extends Model
{
    protected $table = 'question_options';

    protected $fillable = ['question_id', 'option', 'is_correct'];

    protected $casts = ['is_correct' => 'boolean'];

    public function question(): BelongsTo
    {
        return $this->belongsTo(QuestionModel::class, 'question_id');
    }

    public function studentAnswers(): HasMany
    {
        return $this->hasMany(StudentAnswer::class, 'question_option_id');
    }
}
