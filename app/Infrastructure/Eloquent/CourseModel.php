<?php

namespace App\Infrastructure\Eloquent;

use App\Domain\Course\CourseCategory;
use App\Domain\Course\CourseStatus;
use App\Models\Lesson;
use Database\Factories\CourseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseModel extends Model
{
    use HasFactory;

    protected $table = 'courses';

    protected $fillable = ['title', 'description', 'category', 'status'];

    protected $casts = [
        'category' => CourseCategory::class,
        'status' => CourseStatus::class,
    ];

    protected static function newFactory(): CourseFactory
    {
        return CourseFactory::new();
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'course_id')->orderBy('sort_order');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(StudentModel::class, 'course_student', 'course_id', 'student_id')
            ->withPivot(['status']);
    }
}
