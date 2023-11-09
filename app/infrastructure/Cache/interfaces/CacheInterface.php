<?php

namespace Infrastructure\Cache\interfaces;

interface CacheInterface
{
    public const HOST_ENV_VAR = 'CACHE_HOST';

    public function set(string $key, string $value): void;
    public function get(string $key): ?string;
    public function getCache(): CacheInterface;
}