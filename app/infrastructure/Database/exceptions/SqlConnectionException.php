<?php

namespace Infrastructure\Database\exceptions;

use Infrastructure\Exceptions\CodeMessageError;
use Infrastructure\Exceptions\ExceptionEnum;
use Throwable;

class SqlConnectionException extends CodeMessageError
{
    public function __construct(string $message = "", int $level = 0, ?Throwable $previous = null)
    {
        parent::__construct(ExceptionEnum::SQl_CONNECTION, $message, 503, $level, $previous);
    }
}