<?php

namespace Tests\Unit\Repository;

use App\Enums\AbilityEnum;
use App\Models\Ability;
use App\Models\User;
use App\Repositories\ManagerRepository;
use App\Support\Types\AbilitiesType;
use App\ValueObjects\Users\NameObject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManagerRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_should_sync_abilities_for_the_manager_role(): void
    {
        $repository = new ManagerRepository();

        $manager = $repository->create(
            email: fake()->email(),
            fullName: new NameObject(
                firstName: 'john',
                lastName: 'duo'
            ),
            abilities: new AbilitiesType($abilities = [
                AbilityEnum::AddProduct,
                AbilityEnum::GetAllSettings,
            ]),
            password: 'password'
        );

        foreach ($abilities as $index => $ability) {
            $this->assertSame(
                $manager->roles->first()->abilities[$index]->getSlug(),
                $ability->slugify()
            );
        }
        $this->assertCount(1, $manager->roles);
        $this->assertDatabaseCount(Ability::TABLE, 2);
    }

    public function test_it_should_store_password_in_hashed_form(): void
    {
        $repository = new ManagerRepository();

        $manager = $repository->create(
            email: $email = fake()->email(),
            fullName: $name = new NameObject(
                firstName: 'john',
                lastName: 'duo'
            ),
            abilities: new AbilitiesType([]),
            password: $password = 'plain-password'
        );

        $this->assertIsInt($manager->getId());
        $this->assertDatabaseMissing(User::TABLE, [
            User::EMAIL => $email,
            User::FIRST_NAME => $name->getFirstName(),
            User::LAST_NAME => $name->getLastName(),
            User::PASSWORD => $password,
        ]);
    }

    public function test_it_should_be_able_to_add_new_manager(): void
    {
        $repository = new ManagerRepository();

        $manager = $repository->create(
            email: $email = fake()->email(),
            fullName: $name = new NameObject(
                firstName: 'john',
                lastName: 'duo'
            ),
            abilities: new AbilitiesType([]),
            password: 'plain-password'
        );

        $this->assertInstanceOf(User::class, $manager);
        $this->assertDatabaseHas(User::TABLE, [
            User::EMAIL => $email,
            User::FIRST_NAME => $name->getFirstName(),
            User::LAST_NAME => $name->getLastName(),
        ]);
    }
}
