<?php

namespace Infrastructure\Repository;

use Attribute;

/**
 * Attribut à porter sur les properties d'entité pour lier une propriété à une colonne en base de données
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class DbColumn
{
    public function __construct(
        public string $columnName
    ) {}
}