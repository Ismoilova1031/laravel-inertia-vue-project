<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\TaskType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\TaskSubmission;

class Task extends Model
{
    protected $fillable = [
        'lesson_id',
        'task_type',
        'title',
        'description',
    ];

    protected $casts = [
        'task_type' => TaskType::class,
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function taskSubmissions(): HasMany
    {
        return $this->hasMany(TaskSubmission::class);
    }
}
