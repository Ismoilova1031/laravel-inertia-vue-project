<?php

namespace App\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Model;
use App\Domain\Enrollment\EnrollmentStatus;

class EnrollmentModel extends Model
{
    protected $table = 'course_student';

    protected $fillable = [
        'course_id',
        'student_id',
        'status',
        'enrolled_at',
        'deactivated_at',
    ];

    protected $casts = [
        'enrolled_at'    => 'immutable_datetime',
        'deactivated_at' => 'immutable_datetime',
        'status' => EnrollmentStatus::class,
    ];
}