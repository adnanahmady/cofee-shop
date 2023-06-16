<?php

namespace App\Exceptions;

use App\ExceptionMessages\ViolationMessageInterface;
use Illuminate\Validation\ValidationException;

class InvalidCredentialException extends ValidationException
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
