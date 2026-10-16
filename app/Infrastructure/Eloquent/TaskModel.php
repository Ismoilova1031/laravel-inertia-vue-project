<?php

namespace App\Infrastructure\Eloquent;

use App\Domain\Assessment\TaskType;
use App\Models\TaskSubmission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskModel extends Model
{
    protected $table = 'tasks';

    protected $fillable = ['lesson_id', 'task_type', 'allowed_file_extensions'];

    protected $casts = [
        'task_type' => TaskType::class,
        'allowed_file_extensions' => 'array',
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(LessonModel::class, 'lesson_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(QuestionModel::class, 'task_id')->orderBy('sort_order');
    }

    public function taskSubmissions(): HasMany
    {
        return $this->hasMany(TaskSubmission::class, 'task_id');
    }
}