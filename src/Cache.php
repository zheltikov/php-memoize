<?php

namespace Zheltikov\Memoize;

/**
 * Interface Cache
 * @package Zheltikov\Memoize
 */
interface Cache
{
    /**
     * @return $this
     */
    public function clear(): self;

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set(string $key, $value): self;

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key);

    /**
     * @param string $key
     * @return bool
     */
    public function isset(string $key): bool;
}
