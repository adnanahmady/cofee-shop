<?php

namespace App\DataTransferObjects\Orders;

use App\Interfaces\IdInterface;
use App\Models\User;

// phpcs:disable PSR1.Files.SideEffects
final readonly class StoreOrderDataDto
{
    public function __construct(
        private User $user,
        private IdInterface $deliveryType,
        private IdInterface $address
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
