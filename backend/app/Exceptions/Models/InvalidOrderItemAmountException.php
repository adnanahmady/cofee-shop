<?php

namespace App\Exceptions\Models;

use App\ExceptionMessages\ViolationMessageInterface;
use Illuminate\Validation\ValidationException;

class InvalidOrderItemAmountException extends ValidationException
{
    public static function throwIf(
        bool $condition,
        ViolationMessageInterface $message
    ): void {
        if ($condition) {
            throw static::withMessages($message->toArray());
        }
    }
}
