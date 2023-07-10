<?php

namespace Tests\Unit\StateDeterminer;

use App\Support\OrderStateDeterminer\Contracts\ChoiceHolderInterface;
use App\Support\OrderStateDeterminer\Determiner;
use App\Support\OrderStateDeterminer\Values\DeliveredValue;
use App\Support\OrderStateDeterminer\Values\PreparationValue;
use App\Support\OrderStateDeterminer\Values\ReadyValue;
use App\Support\OrderStateDeterminer\Values\WaitingValue;
use App\Support\Values\ValueInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class DeterminerTest extends TestCase
{
    public static function dataProviderForChangeStateTest(): array
    {
        return [
            'it should determine next state as preparation when unknown state is given' => [
                'some-state',
                new PreparationValue(),
                new ChoiceHolder(forward: true),
            ],
            'it should choose waiting when both choices are off' => [
                new WaitingValue(),
                new WaitingValue(),
                new ChoiceHolder(forward: false, rollback: false),
            ],
            'waiting should be the first state' => [
                new WaitingValue(),
                new WaitingValue(),
                new ChoiceHolder(rollback: true),
            ],
            'it should be able to roll back from preparation to waiting' => [
                new PreparationValue(),
                new WaitingValue(),
                new ChoiceHolder(rollback: true),
            ],
            'it should be able to roll back from ready to preparation' => [
                new ReadyValue(),
                new PreparationValue(),
                new ChoiceHolder(rollback: true),
            ],
            'it should be able to roll back from delivered to ready' => [
                new DeliveredValue(),
                new ReadyValue(),
                new ChoiceHolder(rollback: true),
            ],
            'delivered should be the latest state' => [
                new DeliveredValue(),
                new DeliveredValue(),
                new ChoiceHolder(forward: true),
            ],
            'it should be able to forward from ready to delivered' => [
                new ReadyValue(),
                new DeliveredValue(),
                new ChoiceHolder(forward: true),
            ],
            'it should be able to forward from preparation to ready' => [
                new PreparationValue(),
                new ReadyValue(),
                new ChoiceHolder(forward: true),
            ],
            'it should be able to forward from waiting to preparation' => [
                new WaitingValue(),
                new PreparationValue(),
                new ChoiceHolder(forward: true),
            ],
            'it should prioritize forward over roll back' => [
                new WaitingValue(),
                new PreparationValue(),
                new ChoiceHolder(forward: true, rollback: true),
            ],
        ];
    }

    #[DataProvider('dataProviderForChangeStateTest')]
    public function test_change_state(
        string|ValueInterface $currentState,
        ValueInterface $nextState,
        ChoiceHolderInterface $holder
    ): void {
        $determiner = new Determiner(
            choiceHolder: $holder,
            currentState: $currentState
        );

        $status = $determiner->determine();

        $this->assertSame((string) $nextState, (string) $status);
    }
}
