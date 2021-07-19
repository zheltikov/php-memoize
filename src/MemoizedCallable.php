<?php

namespace Zheltikov\Memoize;

use Closure;

/**
 * Class MemoizedCallable
 * @package Zheltikov\Memoize
 */
class MemoizedCallable
{
    /**
     * @var array
     */
    private array $cache = [];

    /**
     * @var string
     */
    private string $hash_algo;

    /**
     * @var callable
     */
    private $callable;

    /**
     * MemoizedCallable constructor.
     * @param callable $callable
     * @throws \Zheltikov\Exceptions\InvariantException
     */
    public function __construct(callable $callable)
    {
        $this->callable = $callable;
        $this->hash_algo = Config::getHashAlgo();
        Config::registerMemoizedCallable($this);
    }

    /**
     * @throws \Zheltikov\Exceptions\InvariantException
     */
    public function __destruct()
    {
        Config::unregisterMemoizedCallable($this);
    }

    /**
     * @param mixed ...$args
     * @return mixed
     */
    public function __invoke(...$args)
    {
        return $this->call(...$args);
    }

    /**
     * @param mixed ...$args
     * @return mixed
     */
    public function call(...$args)
    {
        $key = hash($this->getHashAlgo(), serialize($args));

        // There is no such parameter combo in the cache, compute the result and cache it
        if (!array_key_exists($key, $this->cache)) {
            $this->cache[$key] = call_user_func_array($this->callable, $args);
        }

        return $this->cache[$key];
    }

    /**
     * @return \Closure
     */
    public function getClosure(): Closure
    {
        /**
         * @param mixed ...$args
         * @return mixed
         */
        return function (...$args) {
            return $this->call(...$args);
        };
    }

    /**
     * @param string $hash_algo
     * @return $this
     */
    public function setHashAlgo(string $hash_algo): self
    {
        $this->hash_algo = $hash_algo;
        $this->clearCache();
        return $this;
    }

    /**
     * @return string
     */
    public function getHashAlgo(): string
    {
        return $this->hash_algo;
    }

    /**
     * @return $this
     */
    public function clearCache(): self
    {
        $this->cache = [];
        return $this;
    }
}
