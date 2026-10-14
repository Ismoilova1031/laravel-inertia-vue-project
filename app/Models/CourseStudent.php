<?php

namespace App\Models;

use App\Infrastructure\Eloquent\CourseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseStudent extends Model
{
    protected $fillable = [
        'course_id',
        'student_id',
        'status',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(CourseModel::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
