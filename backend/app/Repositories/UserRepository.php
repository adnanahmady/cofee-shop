<?php

namespace App\Repositories;

use App\Enums\AbilityEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    public function isAbleTo(User $user, AbilityEnum $ability): bool
    {
        return $user->tokenCan($ability->slugify());
    }

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
