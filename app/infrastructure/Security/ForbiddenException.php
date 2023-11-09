<?php

namespace Infrastructure\Security;

use Infrastructure\Exceptions\CodeMessageError;
use Infrastructure\Exceptions\ExceptionEnum;
use Throwable;

class ForbiddenException extends CodeMessageError
{
    public function __construct(string $message = "", int $level = 0, ?Throwable $previous = null)
    {
        parent::__construct(ExceptionEnum::UNAUTHORIZED, $message,403, $level, $previous);
    }
}