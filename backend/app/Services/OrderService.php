<?php

namespace App\Services;

use App\Enums\AbilityEnum;
use App\Models\User;
use App\Repositories\OrderRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderService
{
    private OrderRepository $orderRepository;

    public function __construct()
    {
        $this->orderRepository = new OrderRepository();
    }

    public function getPaginated(
        User $user,
        int $page,
        int $perPage,
    ): LengthAwarePaginator {
        $isManager = $user->tokenCan(
            AbilityEnum::SetOrderStatus->slugify()
        );

        if ($isManager) {
            return $this->orderRepository->getPaginated(
                page: $page,
                perPage: $perPage
            );
        }

        return $this->orderRepository->getPaginatedForUser(
            user: $user,
            page: $page,
            perPage: $perPage
        );
    }
}
