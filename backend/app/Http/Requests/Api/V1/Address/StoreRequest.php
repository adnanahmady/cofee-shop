<?php

namespace App\Http\Requests\Api\V1\Address;

use App\Http\Requests\Api\V1\AbstractFormRequest;
use App\Models\User;
use App\Repositories\UserRepository;

class StoreRequest extends AbstractFormRequest
{
    public const TITLE = 'title';
    public const CITY = 'city';
    public const STREET = 'street';
    public const PLATE_NUMBER = 'plate_number';
    public const POSTAL_CODE = 'postal_code';
    public const DESCRIPTION = 'description';
    public const USER_ID = 'user_id';

    public function rules(): array
    {
        return [
            self::TITLE => 'required',
            self::CITY => 'required',
            self::STREET => 'required',
            self::PLATE_NUMBER => 'required',
            self::POSTAL_CODE => 'required',
            self::DESCRIPTION => 'string|min:10|max:200',
            self::USER_ID => sprintf(
                'int|exists:%s,%s',
                User::TABLE,
                User::ID
            ),
        ];
    }

    public function getTitle(): string
    {
        return $this->get(self::TITLE);
    }

    public function getCity(): string
    {
        return $this->get(self::CITY);
    }

    public function getStreet(): string
    {
        return $this->get(self::STREET);
    }

    public function getPlateNumber(): string
    {
        return $this->get(self::PLATE_NUMBER);
    }

    public function getPostalCode(): string
    {
        return $this->get(self::POSTAL_CODE);
    }

    public function getDescription(): string
    {
        return $this->get(self::DESCRIPTION);
    }

    public function getSpecifiedUser(): null|User
    {
        $repository = new UserRepository();
        $id = $this->get(self::USER_ID);

        return $id ? $repository->findById(id: $id) : null;
    }
}
