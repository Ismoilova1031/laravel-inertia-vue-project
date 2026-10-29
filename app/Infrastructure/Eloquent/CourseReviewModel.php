<?php

namespace App\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Model;

class CourseReviewModel extends Model
{
    protected $table = 'course_reviews';

    protected $fillable = ['course_id', 'student_id', 'rating', 'comment'];
}