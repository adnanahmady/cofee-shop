<?php

namespace App\Exceptions\Models;

use App\ExceptionMessages\ExceptionMessageInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UnavailableRelationException extends HttpException
{
    public static function throwIf(
        bool $condition,
        ExceptionMessageInterface $message
    ): void {
        if ($condition) {
            throw new static(500, $message);
        }
    }
}
