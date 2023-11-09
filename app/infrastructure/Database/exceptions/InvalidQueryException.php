<?php

namespace Infrastructure\Database\exceptions;

use Infrastructure\Exceptions\AppError;
use Infrastructure\Exceptions\ExceptionEnum;
use PDOException;
use Throwable;

class InvalidQueryException extends AppError
{
    private const MYSQL_DUPLICATE = 1062;
    private SqlExceptionTypeEnum $type;
    private string $devInfos;
    public function __construct(string $message = "", int $level = 0, ?Throwable $previous = null)
    {
        parent::__construct(ExceptionEnum::SQL_QUERY, $message, 400, $level, $previous);
    }

    public function getType(): SqlExceptionTypeEnum
    {
        return $this->type;
    }

    public function getDevInfos(): string
    {
        return $this->devInfos;
    }

    static public function buildFromException(PDOException $pdoException): self
    {
        $mysqlCodeError = $pdoException->errorInfo[1];
        $exception = new self();
        $exception->devInfos = $pdoException->message;
        $exception->type = match ($mysqlCodeError) {
            self::MYSQL_DUPLICATE => SqlExceptionTypeEnum::DUPLICATE,
            default => SqlExceptionTypeEnum::DEFAULT
        };

        return $exception;
    }
}