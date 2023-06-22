<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    public function findByEmail(string $email): User|null
    {
        return User::where('email', $email)->first();
    }

    public function saveOrders(
        User $user,
        Collection $orders
    ): Collection|iterable {
        return $user->orders()->saveMany($orders);
    }
}
