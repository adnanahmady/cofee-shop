<?php

namespace App\Loaders\Roles;

use App\Models\Role;
use App\Repositories\RoleRepository;

class AbilityLoader implements AbilityLoaderInterface
{
    private RoleRepository $roleRepository;
    private null|Role $role;

    public function __construct(string $role)
    {
        $this->roleRepository = new RoleRepository();
        $this->role = $this->roleRepository->findBySlug($role);
    }

    public function getAbilities(string ...$defaults): array
    {
        if (null === $this->role) {
            return $defaults;
        }

        return $this->roleRepository
            ->getAbilities($this->role) ?? $defaults;
    }
}
