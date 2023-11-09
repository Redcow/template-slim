<?php

namespace Config;

class EnvProvider
{
    private static array $envVars = [];

    public static function set(string $varName, mixed $value): void
    {
        self::$envVars[$varName] = $value;
    }

    /**
     * @throws MissingEnvVarException
     */
    public static function get(string $varName): mixed
    {
        return self::$envVars[$varName] ?? throw new MissingEnvVarException($varName);
    }
}