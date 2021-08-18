<?php

namespace Zheltikov\Memoize;

/**
 * This is the default cache implementation.
 *
 * It is an in-memory (array) cache, thus, not very memory-efficient :(,
 * but suitable for most cases.
 * This cache data lives only during the current request.
 *
 * If you need an inter-process cache, see for example:
 * https://github.com/zheltikov/php-memoize-redis
 *
 * ...or, implement your own!
 */
class DefaultCache implements Cache
{
    /**
     * @var array
     */
    private array $cache = [];

    /**
     * @return $this
     */
    public function clear(): self
    {
        $this->cache = [];
        return $this;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set(string $key, $value): self
    {
        $this->cache[$key] = $value;
        return $this;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->cache[$key];
    }

    /**
     * @param string $key
     * @return bool
     */
    public function isset(string $key): bool
    {
        return array_key_exists($key, $this->cache);
    }
}
