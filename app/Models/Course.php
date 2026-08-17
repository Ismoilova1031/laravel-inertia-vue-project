<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\CourseCategory;
use App\Enums\CourseStatus;

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
}
