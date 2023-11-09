<?php

namespace Service;

use Config\EnvProvider;
use Infrastructure\Security\AuthSecurity;
use Infrastructure\Security\UnauthorizedException;
use Jose\Component\Checker;
use Jose\Component\Checker\ClaimCheckerManager;
use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Core\JWK;
use Jose\Component\Signature\Algorithm\HS256;
use Jose\Component\Signature\JWSBuilder;
use Jose\Component\Signature\JWSVerifier;
use Jose\Component\Signature\Serializer\CompactSerializer;
use Jose\Component\Signature\Serializer\JWSSerializerManager;
use Model\Security\JwtServiceInterface;

class JwtService implements JwtServiceInterface
{
    private ?String $payload = null;

    public function build(array $payload = []): string
    {
        $jwk = $this->getJWK();

        $jwsBuilder = new JWSBuilder($this->getAlgorithmManager());

        $payload = $this->buildPayload($payload);

        $jws = $jwsBuilder->create()
            ->withPayload($payload)
            ->addSignature($jwk, ['alg' => 'HS256'])
            ->build();

        $serializer = new CompactSerializer();

        return $serializer->serialize($jws, 0);
    }

    /**
     * @throws UnauthorizedException
     */
    public function decrypt(string $token): void
    {
        $jwsVerifier = new JWSVerifier($this->getAlgorithmManager());

        $jwk = $this->getJWK();

        $serializerManager = new JWSSerializerManager([
            new CompactSerializer()
        ]);

        $jws = $serializerManager->unserialize($token);

        if(!$jwsVerifier->verifyWithKey($jws, $jwk, 0)) {
            throw new UnauthorizedException();
        }

        $this->payload = $jws->getPayload();
    }

    public function getPayload(): ?array
    {
        $claimCheckerManager = new ClaimCheckerManager(
            [
                new Checker\IssuedAtChecker(),
                new Checker\NotBeforeChecker(),
                new Checker\ExpirationTimeChecker(),
                new Checker\AudienceChecker(EnvProvider::get(AuthSecurity::APP_NAME)),
            ]
        );

        $claims = json_decode($this->payload, true);
        $claimCheckerManager->check($claims);
        return $claims;
    }

    private function buildPayload(array $additionalData): string
    {
        return json_encode(array_merge(
            [
                'iat' => time(),
                'nbf' => time(),
                'exp' => time() + 3600,
                'iss' => 'Cogelec',
                'aud' => EnvProvider::get(AuthSecurity::APP_NAME),
            ],
            $additionalData
        ));
    }

    private function getAlgorithmManager(): AlgorithmManager
    {
        return new AlgorithmManager([
            new HS256(),
        ]);
    }

    private function getJWK(): JWK
    {
        return new JWK([
            'kty' => 'oct',
            'k' => EnvProvider::get(AuthSecurity::JWT_SECRET)
        ]);
    }
}