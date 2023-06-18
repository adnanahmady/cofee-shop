<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function findByEmail(string $email): User|null
    {
        return User::where('email', $email)->first();
    }
}
