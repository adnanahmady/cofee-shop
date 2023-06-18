<?php

namespace Tests\Traits;

use App\Models\User;
use Laravel\Sanctum\Sanctum;

trait LetsBeTrait
{
    public function letsBe(User $user, array $abilities = []): User
    {
        Sanctum::actingAs(user: $user, abilities: $abilities, guard: 'api');

        return $user;
    }
}
