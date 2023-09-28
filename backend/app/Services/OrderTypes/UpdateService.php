<?php

namespace App\Services\OrderTypes;

use App\Enums\AbilityEnum;
use App\ExceptionMessages\ForbiddenActionExceptionMessage;
use App\Exceptions\ForbiddenException;
use App\Http\Requests\Api\V1\OrderTypes\UpdateRequest;
use App\Models\Order;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;

class UpdateService
{
    public function __construct(
        private readonly UpdateRequest $request,
        private readonly UserRepository $userRepository,
        private readonly OrderRepository $orderRepository,
    ) {}

    public function update(Order $order): Order
    {
        ForbiddenException::throwUnless(
            $this->isAbleToPerform($order),
            new ForbiddenActionExceptionMessage()
        );
        $this->orderRepository->updateDeliveryType(
            $order,
            $this->request->getDeliveryType()
        );

        return $order;
    }

    private function isAbleToPerform(Order $order): bool
    {
        return $this->canSetType() || $this->isTheCustomer($order);
    }

    private function canSetType(): bool
    {
        return $this->userRepository->isAbleTo(
            $this->request->user(),
            AbilityEnum::SetOrderType
        );
    }

    private function isTheCustomer(Order $order): bool
    {
        return $this->orderRepository->doesBelongToUser(
            $order,
            $this->request->user()
        );
    }
}
