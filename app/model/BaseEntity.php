<?php

namespace Model;

use Infrastructure\Repository\DbColumn;
use JsonSerializable;

/**
 * Classe abstraite à étendre pour chaque entité métier
 * Sans elle, pas de bénéfice des automatisations sur les Repository
 */
abstract class BaseEntity implements JsonSerializable
{
    public function __construct(
        #[DbColumn('id')]
        public readonly ?int $id
    ){}

    public function jsonSerialize(): array
    {
        return ["id" => $this->id];
    }
}