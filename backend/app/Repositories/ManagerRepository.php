<?php

namespace App\Repositories;

use App\Models\Role;
use App\Models\User;
use App\Support\Types\AbilitiesType;
use App\ValueObjects\Users\NameObject;

class ManagerRepository
{
    public function create(
        string $email,
        NameObject $fullName,
        AbilitiesType $abilities,
        string $password
    ): User {
        $user = new User();
        $user->setEmail($email);
        $user->setName($fullName);
        $user->setPassword($password);
        $user->save();
        $user->roles()->save(
            $this->prepareManagerRole($abilities)
        );

        return $user;
    }

    private function prepareManagerRole(AbilitiesType $abilities): Role
    {
        $repository = new RoleRepository();
        $role = $repository->firstOrCreate(slug: 'manager', name: 'Manager');
        $repository->syncAbilities(role: $role, abilities: $abilities);

        return $role;
    }
}
