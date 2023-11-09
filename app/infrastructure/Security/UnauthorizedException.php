<?php

namespace Infrastructure\Security;

use Infrastructure\Exceptions\AppError;
use Infrastructure\Exceptions\ExceptionEnum;
use Throwable;

class UnauthorizedException extends AppError
{
    public function __construct(string $message = "", int $level = 0, ?Throwable $previous = null)
    {
        parent::__construct(ExceptionEnum::UNAUTHORIZED, $message, 401, $level, $previous);
    }
}