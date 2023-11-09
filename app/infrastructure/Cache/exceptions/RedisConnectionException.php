<?php

namespace Infrastructure\Cache\exceptions;

use Infrastructure\Exceptions\AppError;
use Infrastructure\Exceptions\ExceptionEnum;
use Throwable;

class RedisConnectionException extends AppError
{
    public function __construct(string $message = "", int $level = 0, ?Throwable $previous = null)
    {
        parent::__construct(ExceptionEnum::CACHE_CONNECTION, $message, 503, $level, $previous);
    }
}