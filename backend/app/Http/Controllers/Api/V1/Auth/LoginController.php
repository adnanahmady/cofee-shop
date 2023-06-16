<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Resources\Api\V1\Auth\Login\LoggedInResource;
use App\Repositories\UserRepository;

class LoginController extends Controller
{
    public function login(
        LoginRequest $request,
        UserRepository $userRepository
    ): LoggedInResource {
        [$token, $user] = $userRepository->loginByEmail(
            $request->getEmail(),
            $request->getPassword()
        );

        return new LoggedInResource($token, $user);
    }
}
