<?php

namespace Infrastructure\Mail;

use Infrastructure\Exceptions\CodeMessageError;
use Infrastructure\Exceptions\ExceptionEnum;
use Throwable;

class EmailException extends CodeMessageError
{
    public function __construct(array $to, int $level = 0, ?Throwable $previous = null)
    {
        $people = join(', ', $to);
        $message = "The mail for {$people} has failed";
        parent::__construct(ExceptionEnum::MAIL, $message, 503, $level, $previous);
    }
}