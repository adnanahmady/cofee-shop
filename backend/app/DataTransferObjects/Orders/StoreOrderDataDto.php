<?php

namespace App\DataTransferObjects\Orders;

use App\Interfaces\IdInterface;
use App\Models\User;

final class StoreOrderDataDto
{
    public function __construct(
        private readonly User $user,
        private readonly IdInterface $deliveryType,
        private readonly IdInterface $address
    ) {}

    public function getUser(): User
    {
        return $this->user;
    }

    public function getDeliveryType(): IdInterface
    {
        return $this->deliveryType;
    }

    public function getAddress(): IdInterface
    {
        return $this->address;
    }
}
