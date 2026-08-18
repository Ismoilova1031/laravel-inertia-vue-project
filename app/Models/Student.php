<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\CourseStudent;
use App\Models\LessonStudent;
use App\Models\TaskSubmission;
use App\Models\Notification;

class Student extends Model
{
    protected $fillable = [
        'name',
        'surname',
        'email',
        'username',
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function courseStudents(): HasMany
    {
        return $this->hasMany(CourseStudent::class);
    }

    public function lessonStudents(): HasMany
    {
        return $this->hasMany(LessonStudent::class);
    }

    public function taskSubmissions(): HasMany
    {
        return $this->hasMany(TaskSubmission::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }
}
