<?php

namespace App\Services;

use App\Enums\AbilityEnum;
use App\Models\User;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderService
{
    public function __construct(
        private readonly OrderRepository $orderRepository,
        private readonly UserRepository $userRepository
    ) {
    }

    public function getPaginated(
        User $user,
        int $page,
        int $perPage,
    ): LengthAwarePaginator {
        $isManager = $this->userRepository->isAbleTo(
            $user,
            AbilityEnum::SetOrderStatus
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
