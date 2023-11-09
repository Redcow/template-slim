<?php

namespace Infrastructure\Security;

use Service\JwtService;

class AuthSecurity
{
    public const APP_NAME = 'APP_NAME';
    public const JWT_SECRET = 'JWT_SECRET';
    protected static bool $isAllowed = false;
    protected static RoleEnum $userRole = RoleEnum::ANONYMOUS;
    protected static int $userId;

    /**
     * Traite le jwt fournis par le client
     * @param string $jwt
     * @return void
     * @throws UnauthorizedException
     */
    public static function extractJwt(string $jwt): void
    {
        $jwtManager = new JwtService();

        $jwtManager->decrypt($jwt);

       $payload = $jwtManager->getPayload();

        self::$userId = $payload['uid'];
        self::$userRole = RoleEnum::USER;
        self::$isAllowed = true;
    }

    public static function allowPublic(): void
    {
        self::$userRole = RoleEnum::ANONYMOUS;
        self::$isAllowed = true;
    }

    /**
     * Contrôle si le client est authorisé (à utiliser après le Firewall)
     * @return void
     * @throws UnauthorizedException
     */
    public static function checkpoint(): void
    {
        if(!self::$isAllowed) {
            throw new UnauthorizedException();
        }
    }

    /**
     * Retourne l'id de l'utilisateur connecté
     * @return int|null
     * @throws UnauthorizedException
     */
    public static function getUserId(): ?int
    {
        self::checkpoint();
        if(!isset(self::$userId)) throw new UnauthorizedException("Vous devez vous connectez (from getUserId)");
        return self::$userId;
    }
}