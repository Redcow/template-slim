<?php

namespace Infrastructure\Cache\exceptions;

use Infrastructure\Exceptions\CodeMessageError;
use Infrastructure\Exceptions\ExceptionEnum;
use Throwable;

class RedisConnectionException extends CodeMessageError
{
    public function __construct(string $message = "", int $level = 0, ?Throwable $previous = null)
    {
        parent::__construct(ExceptionEnum::CACHE_CONNECTION, $message, 503, $level, $previous);
    }
}