<?php

namespace Infrastructure\Entity;

use Model\BaseEntity;
use ReflectionClass;
use ReflectionProperty;

/**
 * Classe statique permettant d'analyser les classes d'entité pour extraire les données liées à la ligne en base de données
 */
class EntityAnalyzer extends EntityToolBox
{
    private static BaseEntity $entity;

    /**
     * retourne les propriétés liées aux colonnes de la table SQL au format [column => value]
     * @param BaseEntity $entity
     * @return array<string, string>
     */
    static public function extractProperties(BaseEntity $entity): array
    {
        self::$entity = $entity;
        $reflect = new ReflectionClass($entity);

        $dbProperties = self::filterDbProperties(...$reflect->getProperties());

        return self::buildValues(...$dbProperties);
    }

    /**
     * détermine si l'entité appartient à un compte ou non
     * @param BaseEntity $entity
     * @return bool
     */
    static public function isProtected(BaseEntity $entity): bool
    {
        return EntityProtection::isProtected($entity);
    }

    /**
     * construit le résultat des propriétés fournies au format [column => value]
     * @param ReflectionProperty ...$dbProperties
     * @return array<string, string>
     */
    static private function buildValues(ReflectionProperty ...$dbProperties): array
    {
        return array_reduce(
            $dbProperties,
            function (array $properties, ReflectionProperty $property) {
                $columnName = self::getPropertyColumnName($property);
                $columnValue = self::getPropertyValueAsString($property);
                $properties[$columnName] = $columnValue;
                return $properties;
            },
            []
        );
    }

    /**
     * retourne la valeur de la propriété au format string pour assurer la bonne insertion en base de données
     * @param ReflectionProperty $property
     * @return string
     */
    static private function getPropertyValueAsString(ReflectionProperty $property): string
    {
        $value = $property->getValue(self::$entity);

        $ISO_860 = 'c';

        if($value instanceof \DateTimeInterface) {
            return $value->format($ISO_860);
        }

        return (string)$value;
    }
}