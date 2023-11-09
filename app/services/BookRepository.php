<?php

namespace Service;

use Infrastructure\QueryBuilder\QueryBuilder;
use Infrastructure\Repository\DbBaseRepository;
use Infrastructure\Security\ForbiddenException;
use Infrastructure\Security\UnauthorizedException;
use Model\BaseEntity;
use Model\Book\BookEntity;
use Model\Book\BookRepositoryInterface;

final class BookRepository
    extends DbBaseRepository
    implements BookRepositoryInterface
{
    private const TABLE = 'books';

    static function getTable(): string
    {
        return self::TABLE;
    }

    public function getByAuthorId(int $authorId): array
    {
        return [];
    }

    /**
     * @throws ForbiddenException|UnauthorizedException
     */
    public function deleteById(int $bookId): void
    {
        $book = $this->find($bookId);

        if($book === null) return;

        $this->delete($book);
    }

    public function countAuthorBooks(int $authorId): int
    {
        $querybuilder = new QueryBuilder();

        $querybuilder->select('b.id')
                     ->from(self::TABLE, 'b')
                     ->where('b.author_id = ?');

        $this->db->query(
            $querybuilder->getQuery(),
            [$authorId]
        );

        return $this->db->count();
    }

    /**
     * @throws UnauthorizedException
     * @throws ForbiddenException
     */
    public function getById(int $bookId): BookEntity
    {
        return $this->find($bookId);
    }

    /**
     * @param array $row
     * @return BookEntity
     */
    function buildRowToEntity(array $row): BookEntity
    {
        return new BookEntity(
            $row['name'],
            (int)$row['author_id'],
            (int)$row['owner_id'],
            (int)$row['id']
        );
    }
}