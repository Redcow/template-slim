<?php

namespace Config;

use Infrastructure\Cache\interfaces\CacheInterface;
use Infrastructure\Database\interfaces\DatabaseInterface;
use Infrastructure\Logger\LogRDB;
use Infrastructure\Mail\MailerInterface;
use Infrastructure\Security\AuthSecurity;

/**
 * charge l'ensemble des variables d'environnement nécessaires à l'app
 * @throws MissingEnvVarException
 */
function loadEnvConfig(): void
{
    loadAppID();
    loadDatabase();
    loadCache();
    loadLogger();
    loadMailer();
}

function loadAppID(): void
{
    requireEnvVar(AuthSecurity::APP_NAME);
    requireEnvVar(AuthSecurity::JWT_SECRET);
}

/**
 * charge les variables d'environnement pour la connexion à la bdd
 * @throws MissingEnvVarException
 */
function loadDatabase(): void
{
    requireEnvVar(DatabaseInterface::HOST_ENV_VAR);
    requireEnvVar(DatabaseInterface::NAME_ENV_VAR);
    requireEnvVar(DatabaseInterface::USER_NAME_ENV_VAR);
    requireEnvVar(DatabaseInterface::USER_PASSWORD_ENV_VAR);
}

function loadCache(): void
{
    requireEnvVar(CacheInterface::HOST_ENV_VAR);
}

/**
 * charge les variables d'environnement pour le logger LogRDB
 * @throws MissingEnvVarException
 */
function loadLogger(): void
{
    requireEnvVar(LogRDB::TCP_PORT, '44001');
    requireEnvVar(LogRDB::TCP_IP, 'logrdb');

    requireEnvVar(LogRDB::IP_ENV_VAR);
    requireEnvVar(LogRDB::LEVEL_ENV_VAR);
    requireEnvVar(LogRDB::SQL_ENV_VAR);
    requireEnvVar(LogRDB::HTTP_ENV_VAR);
    requireEnvVar(LogRDB::APP_NAME_ENV_VAR);
}

function loadMailer(): void
{
    requireEnvVar(MailerInterface::DSN);
}

/**
 * charge une variable d'environnement, lève une exception si non présente
 * et sans valeur par défaut
 * @throws MissingEnvVarException
 */
function requireEnvVar(string $varName, $defaultValue = false): void
{
    if(!($value = getenv($varName))) {
        if($defaultValue) {
            $value = $defaultValue;
        } else {
            throw new MissingEnvVarException($varName);
        }
    }
    EnvProvider::set($varName, $value);
}
