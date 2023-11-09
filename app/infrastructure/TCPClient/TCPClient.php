<?php

namespace Infrastructure\TCPClient;

use Infrastructure\Exceptions\AppError;
use Socket;

final class TCPClient
{

    private Socket $socket;
    private function __construct() {

    }

    /**
     * @throws AppError
     */
    static public function init(string $ip, string $port): self
    {
        $client = new TCPClient();
        $client->socket = $client->createSocket();
        $client->connectSocket($ip, $port);

        return $client;
    }

    public function sendMessage(string $message): void
    {
        socket_write($this->socket, $message, strlen($message));
        socket_close($this->socket);
    }

    /**
     * @throws TCPConnectionError
     */
    private function createSocket(): Socket
    {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        if($socket === false) {
            throw $this->tcpError();
        }

        return $socket;
    }

    /**
     * @throws TCPConnectionError
     */
    private function connectSocket(string $ip, string $port): void
    {
        $connectionResult = socket_connect($this->socket, $ip, $port);

        if($connectionResult === false) {
            throw $this->tcpError();
        }
    }

    private function tcpError(): TCPConnectionError
    {
        return new TCPConnectionError(
            "Erreur lors de la création de connection TCP"
        );
    }
}