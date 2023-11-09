<?php

namespace Infrastructure\Logger;

use Config\EnvProvider;
use Infrastructure\Security\AuthSecurity;
use Infrastructure\TCPClient\TCPClient;

class LogRDB
{
    public const TCP_PORT = 'LOGRDB_TCP_PORT';
    public const TCP_IP = 'LOGRDB_TCP_IP';
    public const IP_ENV_VAR = 'LOGRDB_IP';
    public const LEVEL_ENV_VAR = 'LOGRDB_LEVEL';
    public const SQL_ENV_VAR = 'LOGRDB_SQL';
    public const HTTP_ENV_VAR = 'LOGRDB_HTTP';
    public const APP_NAME_ENV_VAR = 'LOGRDB_APP_NAME';

    public static function log(LogCriticalityEnum $criticality = LogCriticalityEnum::LOG, string $text = '', $data = []): void
    {
        $payload = json_encode([
            'app' => EnvProvider::get(AuthSecurity::APP_NAME),
            'data' => $data,
            'log' => strtolower($criticality->name),
            'text' => $text
        ]);

        self::send($payload);
    }

    public static function logSQL(string $query, array $queryParams): void
    {
        foreach ($queryParams as $key => $value) {
            if(is_string($key)) {
                if(str_starts_with($key, ':')) {
                    $query = str_replace($key, $value, $query);
                } else {
                    $query = str_replace(":$key", $value, $query);
                }
            } else {
                $i = 0;
                while ($i < sizeof($queryParams)) {
                    $pos = strpos($query, '?');
                    if ($pos === false) {
                        break;
                    } else {
                        $query = substr($query, 0, $pos) . '"' . $queryParams[$i] . '"' . substr($query, $pos + 1);
                    }
                    $i++;
                }
            }
        }

        self::log(LogCriticalityEnum::SQL, $query, $queryParams);
    }

    private static function send(string $message): void
    {
        $port = EnvProvider::get(self::TCP_PORT);
        $ip = EnvProvider::get(self::TCP_IP);

        TCPClient::init($ip, $port)->sendMessage($message);
    }
}