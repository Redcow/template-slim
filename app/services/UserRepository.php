<?php

namespace Service;

use Infrastructure\QueryBuilder\QueryBuilder;
use Infrastructure\Repository\DbBaseRepository;
use Infrastructure\Security\ForbiddenException;
use Infrastructure\Security\UnauthorizedException;
use Model\User\NewUserEntity;
use Model\User\UserEntity;
use Model\User\UserRepositoryInterface;

final class UserRepository
    extends DbBaseRepository
    implements UserRepositoryInterface
{
    private const TABLE = 'users';

    private string $buildType = UserEntity::class;

    public function connect(string $username, string $password): UserEntity
    {
        try {
            $queryBuilder = new QueryBuilder();

            $queryBuilder->select('u.*')
                ->from(self::TABLE, 'u')
                ->where('u.email = :username');

            $this->db->query(
                $queryBuilder->getQuery(),
                ['username' => $username]
            );

            /** @var UserEntity $user */
            $row = $this->db->fetch();

            if($row === null ) throw new UnauthorizedException('Username or password invalid');

            $user = $this->buildRowToEntity($row);

            if(!$user->verifyPassword($password)) throw new UnauthorizedException('Username or password invalid');

            return $user;
        } catch (\Exception) {
            throw new UnauthorizedException('Username or password invalid');
        }
    }

    /**
     * @throws UnauthorizedException
     * @throws ForbiddenException
     */
    public function save(UserEntity $user): NewUserEntity
    {
        $queryBuilder = new QueryBuilder();

        $queryBuilder->insertInto(self::getTable(), 'email', 'password', 'token', 'type')
                                                        (':email', ':password', 'UUID_SHORT()', ':type');

        $this->db->query(
            $queryBuilder->getQuery(),
            [
                ':email' => $user->email,
                ':password' => $user->password,
                ':type' => $user->type
            ]
        );

        $idUser = $this->db->lastId();

        return $this->buildEntityType(NewUserEntity::class)
                    ->find($idUser);
    }

    static function getTable(): string
    {
        return self::TABLE;
    }

    private function buildEntityType(string $userEntityClass): self
    {
        $this->buildType = $userEntityClass;
        return $this;
    }

    function buildRowToEntity(array $row): UserEntity
    {
        $entity = match ($this->buildType) {
            NewUserEntity::class => new NewUserEntity(
                $row['email'],
                $row['type'],
                $row['token'],
                $row['id']
            ),
            default => new UserEntity(
                email: $row['email'],
                password: $row['password'],
                type: $row['type'],
                id: $row['id']
            ),
        };

        $this->buildEntityType(UserEntity::class);

        return $entity;
    }
}