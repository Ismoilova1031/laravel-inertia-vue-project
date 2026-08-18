<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\CourseCategory;
use App\Enums\CourseStatus;
use App\Models\Lesson;
use App\Models\CourseStudent;
use App\Models\Notification;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'status',
    ];

    protected $casts = [
        'category' => CourseCategory::class,
        'status' => CourseStatus::class,
    ];

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }

    public function courseStudents(): HasMany
    {
        return $this->hasMany(CourseStudent::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }
}
