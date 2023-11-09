<?php

namespace Infrastructure\Logger;

use Config\EnvProvider;
use Infrastructure\Security\AuthSecurity;
use Infrastructure\TCPClient\TCPClient;

class Logger
{
    public const TCP_PORT = 'LOGRDB_TCP_PORT';
    public const TCP_IP = 'LOGRDB_TCP_IP';
    public const APP_NAME_ENV_VAR = 'LOGRDB_APP_NAME';

    public static function log(LogCriticalityEnum $criticality = LogCriticalityEnum::LOG, string $text = '', $data = []): void
    {
        $payload = json_encode([
            'app' => EnvProvider::get(AuthSecurity::APP_NAME),
            'data' => $data,
            'log' => strtolower($criticality->name),
            'text' => $text
        ]);

        // le send sert à envoyer en TCP les logs sur un serveur spécifique
        // on peut imaginer une méthode write pour conserver les logs en local dans des fichiers
        //self::send($payload);
    }

    public static function logSQL(string $query, array $queryParams): void
    {
        self::log(LogCriticalityEnum::SQL, $query, $queryParams);
    }

    private static function send(string $message): void
    {
        $port = EnvProvider::get(self::TCP_PORT);
        $ip = EnvProvider::get(self::TCP_IP);

        TCPClient::init($ip, $port)->sendMessage($message);
    }
}