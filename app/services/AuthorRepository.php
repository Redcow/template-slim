<?php

namespace Service;

use Infrastructure\QueryBuilder\QueryBuilder;
use Infrastructure\Repository\DbBaseRepository;
use Infrastructure\Security\ForbiddenException;
use Infrastructure\Security\UnauthorizedException;
use Model\Author\AuthorEntity;
use Model\Author\AuthorRepositoryInterface;

final class AuthorRepository
    extends DbBaseRepository
    implements AuthorRepositoryInterface
{
    private const TABLE = 'authors';

    public function save(AuthorEntity $author): ?AuthorEntity
    {
        try {
            $id = parent::create($author);
        } catch (\Exception $e) {
            return null;
        }

        return new AuthorEntity(
            $author->firstName,
            $author->lastName,
            $author->owner,
            $id
        );
    }

    /**
     * @throws UnauthorizedException|ForbiddenException
     */
    public function getById(int $authorId): ?AuthorEntity
    {
        return $this->find($authorId);
    }

    /**
     * @throws UnauthorizedException|ForbiddenException
     */
    public function getList(): array
    {
        return $this->findAll();
    }

    /**
     * @throws UnauthorizedException|ForbiddenException
     */
    public function deleteById(int $authorId): void
    {
        // le get permet le controle de l'entity par rapport au user connecté
        $author = $this->find($authorId);
        $this->delete($author);
    }

    public function isStoredAuthor(AuthorEntity $author): bool
    {
        $queryBuilder = new QueryBuilder();

        $queryBuilder->select('a.id')
                     ->from(self::TABLE, 'a')
                     ->where('a.first_name = ?')
                     ->andWhere('a.last_name = ?')
                     ->andWhere('a.owner_id = ?');

        $this->db->query(
            $queryBuilder->getQuery(),
            [
                $author->firstName,
                $author->lastName,
                $author->owner
            ]
        );

        return $this->db->count() > 0 ;
    }

    static function getTable(): string
    {
        return self::TABLE;
    }

    /**
     * @param array $row
     * @return AuthorEntity
     */
    function buildRowToEntity(array $row): AuthorEntity
    {
        return new AuthorEntity(
            $row['first_name'],
            $row['last_name'],
            (int)$row['owner_id'],
            (int)$row['id']
        );
    }
}