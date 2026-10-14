<?php

namespace App\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Model;

class CourseInterestModel extends Model
{
    protected $table = 'course_interests';

    protected $fillable = [
        'course_id',
        'student_id',
        'is_liked',
        'is_planned',
    ];

    protected $casts = [
        'is_liked' => 'boolean',
        'is_planned' => 'boolean',
    ];
}