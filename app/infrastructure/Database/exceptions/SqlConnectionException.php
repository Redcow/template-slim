<?php

namespace Infrastructure\Database\exceptions;

use Infrastructure\Exceptions\AppError;
use Infrastructure\Exceptions\ExceptionEnum;
use Throwable;

class SqlConnectionException extends AppError
{
    public function __construct(string $message = "", int $level = 0, ?Throwable $previous = null)
    {
        parent::__construct(ExceptionEnum::SQl_CONNECTION, $message, 503, $level, $previous);
    }
}