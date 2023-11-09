<?php

namespace Infrastructure\Entity;

use _PHPStan_adbc35a1c\Nette\Neon\Exception;
use Model\BaseEntity;
use ReflectionClass;

class EntityProtection extends EntityToolBox
{
    private static array $memorizedEntities = [];

    public static function isProtected(BaseEntity $entity): bool
    {
        $reflect = new ReflectionClass($entity);
        $namespace = $reflect->getNamespaceName();

        $memorizedEntityProtection = self::getMemorizedNamespace($namespace);

        if($memorizedEntityProtection !== null) {
            return $memorizedEntityProtection;
        }

        self::defineEntityProtection($reflect);

        return self::getMemorizedNamespace($namespace);
    }

    private static function getMemorizedNamespace(string $namespace): ?bool
    {
        return self::$memorizedEntities[$namespace] ?? null;
    }

    private static function defineEntityProtection(ReflectionClass $reflect): void
    {
        $dbProperties = self::filterDbProperties(...$reflect->getProperties());
        $namespace = $reflect->getNamespaceName();

        self::$memorizedEntities[$namespace] = false;

        foreach ($dbProperties as $dbProperty) {
            $columnName = self::getPropertyColumnName($dbProperty);
            if($columnName === 'owner_id') {
                self::$memorizedEntities[$namespace] = true;
                break;
            }
        }
    }
}