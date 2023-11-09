<?php

namespace Model\Author;

use Infrastructure\Repository\DbColumn;
use JsonSerializable;
use Model\BaseEntity;

class AuthorEntity
    extends BaseEntity
    implements JsonSerializable
{
    public function __construct(
        #[DbColumn('first_name')]
        public readonly string $firstName,

        #[DbColumn('last_name')]
        public readonly string $lastName,

        #[DbColumn('owner_id')]
        public readonly ?int $owner = null,

        ?int $id = null
    ) {
        parent::__construct($id);
    }

    public function jsonSerialize(): array
    {
        return array_merge(
            parent::jsonSerialize(),
            [
                "firstName" => $this->firstName,
                "lastName" => $this->lastName,
                "ownerId" => $this->owner
            ]
        );
    }
}