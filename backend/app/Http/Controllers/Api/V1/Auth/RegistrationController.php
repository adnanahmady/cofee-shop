<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Resources\Api\V1\Auth\Register\RegisteredResource;
use App\Repositories\UserRepository;
use App\ValueObjects\Users\NameObject;

class RegistrationController extends Controller
{
    public function register(
        RegisterRequest $request,
        UserRepository $userRepository
    ): RegisteredResource {
        $user = $userRepository->register(
            new NameObject(
                $request->getFirstName(),
                $request->getLastName(),
            ),
            $request->getEmail(),
            $request->getPassword(),
        );

        return new RegisteredResource($user);
    }
}
