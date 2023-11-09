<?php

namespace Infrastructure\Entity;

use Error;
use Infrastructure\Repository\DbColumn;
use ReflectionProperty;

class EntityToolBox
{
    /**
     * Filtre les propriétés de l'entité pour récupérer seulement celles liés à la base de données
     * @param ReflectionProperty ...$properties
     * @return ReflectionProperty[]
     */
    static protected function filterDbProperties(ReflectionProperty ...$properties): array
    {
        return array_filter(
            $properties,
            fn (ReflectionProperty $property) => count(
                $property->getAttributes(
                    DbColumn::class,
                    \ReflectionAttribute::IS_INSTANCEOF)
                ) > 0
        );
    }

    /**
     * Retourne le nom de la column concernée par la property
     * @param ReflectionProperty $property
     * @return string
     */
    static protected function getPropertyColumnName(ReflectionProperty $property): string
    {
        $attributes = $property->getAttributes(DbColumn::class, \ReflectionAttribute::IS_INSTANCEOF);

        if(empty($attributes)) {
            // todo DevError comme MissingEnvVar
            throw new Error('giving wrong db properties');
        }
        /** @var DbColumn $dbColumn */
        $dbColumn = $attributes[0]->newInstance();

        return $dbColumn->columnName;
    }
}