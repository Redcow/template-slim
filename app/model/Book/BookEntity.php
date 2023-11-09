<?php

namespace Model\Book;

use Infrastructure\Repository\DbColumn;
use Model\BaseEntity;

class BookEntity extends BaseEntity
{
    public function __construct(
        #[DbColumn('name')]
        public readonly string $name,

        #[DbColumn('author_id')]
        public readonly int $author,

        #[DbColumn('owner_id')]
        public readonly int $owner,

        ?int $id
    )
    {
        parent::__construct($id);
    }
}