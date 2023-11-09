<?php

namespace Infrastructure\Exceptions;

use Exception;
use Throwable;

class AppError extends Exception implements \JsonSerializable
{
    protected ExceptionEnum $internalCode;
    protected int $level;

    public function __construct(
        ExceptionEnum $internalCode,
        string $message = "",
        int $code = 500,
        int $level = 0,
        ?Throwable $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
        $this->internalCode = $internalCode;
    }

    public function __toString(): string
    {
        return " {$this->internalCode->name}, {$this->message} ";
    }

    public function jsonSerialize(): array
    {
        return [
            "message" => $this->getMessage() ?? "Une erreur est survenue",
        ];
    }
}