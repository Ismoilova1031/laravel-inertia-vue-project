<?php

namespace App\Infrastructure\Eloquent;

use Illuminate\Foundation\Auth\User as Authenticatable;

class StudentModel extends Authenticatable
{
    protected $table = 'students';

    protected $fillable = [
        'name',
        'surname',
        'email',
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
    ];
}
