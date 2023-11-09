<?php

namespace Infrastructure\Security;

use Infrastructure\Exceptions\AppError;
use Infrastructure\Exceptions\ExceptionEnum;
use Throwable;

class ForbiddenException extends AppError
{
    public function __construct(string $message = "", int $level = 0, ?Throwable $previous = null)
    {
        parent::__construct(ExceptionEnum::UNAUTHORIZED, $message,403, $level, $previous);
    }
}