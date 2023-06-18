<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Resources\Api\V1\Auth\Login\LoggedInResource;
use App\Loaders\Roles\AbilityLoader;
use App\Repositories\AuthRepository;

class LoginController extends Controller
{
    public function login(
        LoginRequest $request,
        AuthRepository $authRepository,
    ): LoggedInResource {
        [$token, $user] = $authRepository->loginByEmail(
            $request->getEmail(),
            $request->getPassword(),
            new AbilityLoader($request->getRole())
        );

        return new LoggedInResource($token, $user);
    }
}
