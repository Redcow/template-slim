<?php

namespace Infrastructure\Cache;

use Config\EnvProvider;
use Infrastructure\Cache\exceptions\RedisConnectionException;
use Infrastructure\Cache\interfaces\CacheInterface;
use Redis;

class RedisCache implements CacheInterface
{
    private static ?RedisCache $instance = null;
    private ?Redis $redis = null;

    /**
     * @throws RedisConnectionException
     */
    public function set(string $key, string $value): void
    {
        try {
            $this->redis->set($key, $value);
        } catch (\RedisException) {
            throw new RedisConnectionException();
        }
    }

    /**
     * @throws RedisConnectionException
     */
    public function get(string $key): ?string
    {
        try {
            $value = $this->redis->get($key);

            if($value === false) {
                return null;
            }
            return $value;
        } catch (\RedisException) {
            throw new RedisConnectionException();
        }
    }

    /**
     * @throws RedisConnectionException
     */
    public function getCache(): CacheInterface
    {
        if(self::$instance === null) {
            $this->constructSingleton();
        }
        return self::$instance;
    }

    /**
     * @throws RedisConnectionException
     */
    private function constructSingleton(): void
    {
        try {
            $redis = new Redis();

            $redis->connect(EnvProvider::get(CacheInterface::HOST_ENV_VAR));
            $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_JSON);

            $this->redis = $redis;

            self::$instance = $this;
        } catch (\RedisException) {
            throw new RedisConnectionException();
        }
    }
}