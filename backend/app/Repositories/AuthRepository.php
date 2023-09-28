<?php

namespace App\Repositories;

use App\ExceptionMessages\InvalidEmailMessage;
use App\Exceptions\InvalidCredentialException;
use App\Models\User;
use App\Support\Loaders\Roles\AbilityLoaderInterface;
use App\ValueObjects\Users\NameInterface;
use Illuminate\Support\Facades\Hash;

class AuthRepository
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function login(
        User $user,
        AbilityLoaderInterface $abilities
    ): string {
        return $user->createToken(
            $user->getEmail(),
            $abilities->getAbilities('*')
        )->plainTextToken;
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
     * @throws InvalidCredentialException
     *
     * @return array<string, User>
     */
    public function loginByEmail(
        string $email,
        string $password,
        AbilityLoaderInterface $abilities
    ): array {
        $user = $this->userRepository->findByEmail($email);

        InvalidCredentialException::throwIf(
            !$user || !Hash::check($password, $user->getPassword()),
            new InvalidEmailMessage($email)
        );

        return [$this->login($user, $abilities), $user];
    }
}
