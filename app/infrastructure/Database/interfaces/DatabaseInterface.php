<?php

namespace Infrastructure\Database\interfaces;

use Closure;
use Model\BaseEntity;

interface DatabaseInterface
{
    public const HOST_ENV_VAR = 'DB_HOST';
    public const NAME_ENV_VAR = 'DB_NAME';
    public const USER_NAME_ENV_VAR = 'DB_USER_NAME';
    public const USER_PASSWORD_ENV_VAR = 'DB_USER_PASSWORD';

    public function query(string $query, array $values = []): void;

    public function fetch(): ?array;

    public function fetchAll(): array;

    public function count(): int;

    public function lastId(): int;

    public static function getDb(): DatabaseInterface;
}