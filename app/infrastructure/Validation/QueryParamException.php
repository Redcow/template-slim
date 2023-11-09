<?php

namespace Infrastructure\Validation;

use Infrastructure\Exceptions\CodeMessageError;
use Infrastructure\Exceptions\ExceptionEnum;
use JsonSerializable;
use Throwable;

class QueryParamException extends CodeMessageError implements JsonSerializable
{
    private array $errors;
    public function __construct(array $errors, int $level = 0, ?Throwable $previous = null)
    {
        $this->errors = $errors;

        parent::__construct(
            ExceptionEnum::QUERY_PARAMS,
            "Erreurs dans les informations fournies",
            400, $level, $previous
        );
    }
    public function jsonSerialize(): array
    {
        return [
            'errors' => $this->errors
        ];
    }
}