<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Task;
use App\Models\Student;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\StudentAnswer;

class TaskSubmission extends Model
{
    protected $fillable = [
        'task_id',
        'student_id',
        'attempt',
        'content',
        'file_path',
        'score',
        'submitted_at',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function studentAnswers(): HasMany
    {
        return $this->hasMany(StudentAnswer::class);
    }
}
