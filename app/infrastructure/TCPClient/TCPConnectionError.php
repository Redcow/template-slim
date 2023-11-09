<?php

namespace Infrastructure\TCPClient;

use Infrastructure\Exceptions\CodeMessageError;
use Infrastructure\Exceptions\ExceptionEnum;
use Throwable;

class TCPConnectionError extends CodeMessageError
{
    public function __construct(string $message = "", int $code = 500, int $level = 0,?Throwable $previous = null)
    {
        parent::__construct(
            ExceptionEnum::TCP_SOCKET_CONNECTION,
            $message, $code, $level, $previous);
    }
}