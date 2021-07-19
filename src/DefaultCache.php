<?php

namespace Zheltikov\Memoize;

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
