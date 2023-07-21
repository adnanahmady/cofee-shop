<?php

namespace App\Exceptions;

use App\ExceptionMessages\ExceptionMessageInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ForbiddenException extends HttpException
{
    public static function throwUnless(
        bool $condition,
        ExceptionMessageInterface $message
    ): void {
        if (!$condition) {
            throw new static(Response::HTTP_FORBIDDEN, $message);
        }
    }
}
