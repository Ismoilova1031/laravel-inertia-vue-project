<?php

namespace App\Infrastructure\Eloquent;

use App\Domain\Assessment\Submission\SubmissionStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskSubmissionModel extends Model
{
    protected $table = 'task_submissions';

    protected $fillable = [
        'task_id', 'student_id', 'attempt', 'status', 'content',
        'file_path', 'score', 'teacher_comment', 'submitted_at',
    ];

    protected $casts = [
        'status' => SubmissionStatus::class,
        'submitted_at' => 'immutable_datetime',
    ];

    public function answers(): HasMany
    {
        return $this->hasMany(StudentAnswerModel::class, 'task_submission_id')->orderBy('question_id');
    }
}
