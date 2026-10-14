<?php

namespace App\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Model;

class StudentModel extends Model
{
    protected $table = 'students';

    protected $fillable = [
        'name',
        'surname',
        'email',
        'username',
        'password',
    ];
}