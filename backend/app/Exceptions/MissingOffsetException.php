<?php

namespace App\Exceptions;

use App\ExceptionMessages\ExceptionMessageInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MissingOffsetException extends HttpException
{
    public function __construct(
        ExceptionMessageInterface|string $message = '',
        \Throwable $previous = null,
        array $headers = [],
        int $code = 0
    ) {
        parent::__construct(
            Response::HTTP_INTERNAL_SERVER_ERROR,
            $message,
            $previous,
            $headers,
            $code
        );
    }
}
