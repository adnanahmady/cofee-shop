<?php

namespace App\Repositories;

use App\Enums\AbilityEnum;
use App\ExceptionMessages\ForbiddenActionExceptionMessage;
use App\Exceptions\ForbiddenException;
use App\Http\Requests\Api\V1\Address\StoreRequest;
use App\Models\Address;
use App\Models\User;

class AddressRepository
{
    public function findById(int $id): null|Address
    {
        return Address::find(id: $id);
    }

    public function getUser(Address $address): User
    {
        return $address->user;
    }

    public function create(StoreRequest $request): Address
    {
        $address = new Address();
        $address->setTitle($request->getTitle());
        $address->setUser($this->getAddressOwner($request));
        $address->setCity($request->getCity());
        $address->setStreet($request->getStreet());
        $address->setPlateNumber($request->getPlateNumber());
        $address->setPostalCode($request->getPostalCode());
        $address->setDescription($request->getDescription());
        $address->save();

        return $address;
    }

    private function getAddressOwner(StoreRequest $request): User
    {
        if ($request->getSpecifiedUser()) {
            $userRepository = new UserRepository();
            ForbiddenException::throwUnless(
                $userRepository->isAbleTo(
                    user: $request->user(),
                    ability: AbilityEnum::AddAddressForOthers
                ),
                new ForbiddenActionExceptionMessage()
            );

            return $request->getSpecifiedUser();
        }

        return $request->user();
    }
}
