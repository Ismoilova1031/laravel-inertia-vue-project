<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\User;
class EventsController extends Controller
{
    public function show(User $user)
    {
        return Inertia::render('User/Show', [
            'user' => $user->only(
                'id',
                'name',
                'email'
            ),
        ]);
    }
}