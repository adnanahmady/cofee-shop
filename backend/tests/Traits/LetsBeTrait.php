<?php

namespace Tests\Traits;

use App\Models\User;
use Laravel\Sanctum\Sanctum;

trait LetsBeTrait
{
    public function letsBe(User $user): User
    {
        Sanctum::actingAs($user, guard: 'api');

        return $user;
    }
}
