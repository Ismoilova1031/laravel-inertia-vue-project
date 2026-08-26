<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\LessonType;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Course;
use App\Models\Task;
use App\Models\LessonStudent;
use App\Models\Notification;
use Illuminate\Support\Facades\Storage;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'lesson_type',
        'lesson_content',
        'video_url',
        'sort_order',
    ];

    protected $appends = [
        'video_src',
    ];

    public function getVideoSrcAttribute(): ?string
    {
        if (!$this->video_url) {
            return null;
        }

        return Storage::url($this->video_url);
    }

    protected $casts = [
        'lesson_type' => LessonType::class,
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function lessonStudents(): HasMany
    {
        return $this->hasMany(LessonStudent::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }
}
