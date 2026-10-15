<?php

namespace App\Infrastructure\Eloquent;

use App\Domain\Lesson\LessonType;
use App\Models\Task;
use Database\Factories\LessonFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

final class LessonModel extends Model
{
    use HasFactory;

    protected $table = 'lessons';

    protected $fillable = [
        'course_id', 'title', 'description', 'lesson_type',
        'lesson_content', 'video_url', 'sort_order',
    ];

    protected $casts = [
        'lesson_type' => LessonType::class,
    ];

    protected static function newFactory(): LessonFactory
    {
        return LessonFactory::new();
    }

    public function task(): HasOne
    {
        return $this->hasOne(Task::class, 'lesson_id');
    }
}