<?php

namespace App\Repositories;

use App\ExceptionMessages\InvalidEmailMessage;
use App\Exceptions\InvalidCredentialException;
use App\Models\User;
use App\ValueObjects\Users\NameInterface;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function findByEmail(string $email): User|null
    {
        return User::where('email', $email)->first();
    }

    public function login(User $user): string
    {
        return $user->createToken($user->getEmail())->plainTextToken;
    }

    public function logout(User $user): void
    {
        $user->tokens()->delete();
    }

    public function register(
        NameInterface $name,
        string $email,
        string $password
    ): User {
        $user = new User();
        $user->setName($name);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->save();

        return $user;
    }

    /**
     * Find's the user using the given email
     * and creates a token for the user.
     * After that returns the token and the
     * token user.
     *
     * @param string $email    email
     * @param string $password password
     *
     * @return array<string, User>
     *
     * @throws InvalidCredentialException
     */
    public function loginByEmail(string $email, string $password): array
    {
        $user = $this->findByEmail($email);

        InvalidCredentialException::throwIf(
            !$user || !Hash::check($password, $user->getPassword()),
            new InvalidEmailMessage($email)
        );

        return [$this->login($user), $user];
    }
}
