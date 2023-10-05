<?php

namespace App\Exceptions;

use App\ExceptionMessages\ExceptionMessageInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MissingSettingException extends HttpException
{
    public static function throwIf(
        bool $condition,
        ExceptionMessageInterface $message
    ): void {
        if ($condition) {
            throw new static(Response::HTTP_INTERNAL_SERVER_ERROR, $message);
        }
    }
}
