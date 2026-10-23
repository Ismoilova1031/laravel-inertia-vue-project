<?php

namespace App\Infrastructure\Eloquent;

use App\Domain\User\UserRole;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserModel extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $fillable = ['name', 'username', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'role' => UserRole::class,
        'password' => 'hashed',
    ];
}