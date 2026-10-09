<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

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