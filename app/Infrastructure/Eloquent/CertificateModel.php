<?php

namespace App\Infrastructure\Eloquent;

use App\Domain\Certificate\CourseGrade;
use Illuminate\Database\Eloquent\Model;

class CertificateModel extends Model
{
    protected $table = 'certificates';

    protected $fillable = ['course_id', 'student_id', 'number', 'grade', 'percent', 'issued_at'];

    protected $casts = [
        'grade' => CourseGrade::class,
        'issued_at' => 'immutable_datetime',
    ];
}