<?php

namespace Infrastructure\Database;

use Closure;
use Config\EnvProvider;
use Config\MissingEnvVarException;
use Infrastructure\Database\exceptions\InvalidQueryException;
use Infrastructure\Database\exceptions\SqlConnectionException;
use Infrastructure\Database\interfaces\DatabaseInterface;
use Infrastructure\Logger\Logger;
use Model\BaseEntity;
use PDO;
use PDOStatement;

/**
 * Classe permettant de communiquer avec la DB via PDO
 * Singleton très simpliste, reprendre les logiques de DBI
 * Ne gère qu'une MysqlDatabase par des Env Var pour l'instant
 */
class MysqlDatabase extends DatabaseFactory implements DatabaseInterface
{
    private static ?DatabaseInterface $instance = null;
    protected static ?PDO $pdo = null;
    private ?PDOStatement $queryResult = null;

    /**
     * @throws InvalidQueryException
     */
    public function query(string $query, array $values = []): void
    {
        try {
            $this->queryResult = self::$pdo->prepare($query);
            Logger::logSQL($query, $values);
            $this->queryResult->execute($values);
        } catch (\PDOException $exception) {
            throw InvalidQueryException::buildFromException($exception);
        }

    }

    public function fetch(): ?array
    {
        $row = $this->queryResult->fetch(PDO::FETCH_ASSOC);

        if($row === false) return null;

        return $row;
    }

    public function fetchAll(): array
    {
        $rows = $this->queryResult->fetchAll(PDO::FETCH_ASSOC);

        if($rows === false) return [];

        return $rows;
    }

    /**
     * @throws InvalidQueryException
     */
    public function count(): int
    {
        if($this->queryResult === null) {
            throw new InvalidQueryException('Execute query first');
        }

        return $this->queryResult->rowCount();
    }

    public function lastId(): int
    {
        return self::$pdo->lastInsertId();
    }

    /**
     * @throws MissingEnvVarException|SqlConnectionException
     */
    public static function getDb(): DatabaseInterface
    {
        try {
            if(self::$instance === null) {
                if(self::$pdo !== null) self::$pdo = null;

                $host = EnvProvider::get(self::HOST_ENV_VAR);
                $name = EnvProvider::get(self::NAME_ENV_VAR);
                $username = EnvProvider::get(self::USER_NAME_ENV_VAR);
                $userPassword = EnvProvider::get(self::USER_PASSWORD_ENV_VAR);

                self::$pdo = new PDO(
                    "mysql:host={$host};dbname={$name}",
                    $username,
                    $userPassword,
                    [PDO::ATTR_PERSISTENT => true] /// TODO voir config de DBI
                );

                self::$instance = new static();
            }

            return self::$instance;

        } catch (\PDOException) {
            throw new SqlConnectionException();
        }
    }

    /**
     * @throws MissingEnvVarException
     * @throws SqlConnectionException
     */
    private function constructSingleton(): void
    {
        try {
            if(self::$pdo !== null) self::$pdo = null;

            $host = EnvProvider::get(self::HOST_ENV_VAR);
            $name = EnvProvider::get(self::NAME_ENV_VAR);
            $username = EnvProvider::get(self::USER_NAME_ENV_VAR);
            $userPassword = EnvProvider::get(self::USER_PASSWORD_ENV_VAR);

            self::$pdo = new PDO(
                "mysql:host={$host};dbname={$name}",
                $username,
                $userPassword,
                [PDO::ATTR_PERSISTENT => true] /// TODO voir config de DBI
            );

            self::$instance = $this;

        } catch (\PDOException $exception) {
            throw new SqlConnectionException();
        }
    }
}