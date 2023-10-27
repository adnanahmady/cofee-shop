<?php

namespace Database\Seeders\Development;

use App\Enums\AbilityEnum;
use App\Repositories\ManagerRepository;
use App\Support\Types\AbilitiesType;
use App\ValueObjects\Users\NameObject;
use Illuminate\Database\Seeder;

class ManagerSeeder extends Seeder
{
    public function __construct(
        private readonly ManagerRepository $repository
    ) {}

    /** Run the database seeds. */
    public function run(): void
    {
        $abilities = new AbilitiesType([
            AbilityEnum::AddProduct,
            AbilityEnum::GetAllSettings,
            AbilityEnum::AddAddressForOthers,
            AbilityEnum::SetOrderStatus,
            AbilityEnum::SetOrderType,
            AbilityEnum::SetSettings,
        ]);

        $this->repository->create(
            email: 'john@shop.com',
            fullName: new NameObject(
                firstName: 'John',
                lastName: 'Smith'
            ),
            abilities: $abilities,
            password: 'password'
        );
        $this->repository->create(
            email: 'jain@shop.com',
            fullName: new NameObject(
                firstName: 'Jain',
                lastName: 'Gilman'
            ),
            abilities: $abilities,
            password: 'password'
        );
    }
}
