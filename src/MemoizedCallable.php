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
     * @var callable
     */
    private $callable;

    /**
     * @var \Zheltikov\Memoize\Cache
     */
    private Cache $cache;

    /**
     * @var \Zheltikov\Memoize\KeyGenerator
     */
    private KeyGenerator $key_generator;

    /**
     * MemoizedCallable constructor.
     * @param callable $callable
     * @param \Zheltikov\Memoize\Cache|null $cache
     * @param \Zheltikov\Memoize\KeyGenerator|null $key_generator
     * @throws \Zheltikov\Exceptions\InvariantException
     */
    public function __construct(callable $callable, ?Cache $cache = null, ?KeyGenerator $key_generator = null)
    {
        $this->setCallable($callable);
        $this->setCache($cache ?? new DefaultCache());
        $this->setKeyGenerator($key_generator ?? new DefaultKeyGenerator());
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
     * TODO: optimize this method
     *
     * @param mixed ...$args
     * @return mixed
     */
    public function call(...$args)
    {
        $key = $this->getKeyGenerator()->generateKey($args);

        // There is no such parameter combo in the cache, compute the result and cache it
        if (!$this->getCache()->isset($key)) {
            $value = call_user_func_array($this->getCallable(), $args);
            $this->getCache()->set($key, $value);
            return $value;
        }

        return $this->getCache()->get($key);
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

    // -------------------------------------------------------------------------

    /**
     * @return \Zheltikov\Memoize\Cache
     */
    public function getCache(): Cache
    {
        return $this->cache;
    }

    /**
     * @param \Zheltikov\Memoize\Cache $cache
     * @return $this
     */
    public function setCache(Cache $cache): self
    {
        $this->cache = $cache;
        return $this;
    }

    /**
     * @return \Zheltikov\Memoize\KeyGenerator
     */
    public function getKeyGenerator(): KeyGenerator
    {
        return $this->key_generator;
    }

    /**
     * @param \Zheltikov\Memoize\KeyGenerator $key_generator
     * @return $this
     */
    public function setKeyGenerator(KeyGenerator $key_generator): self
    {
        $this->key_generator = $key_generator;
        return $this;
    }

    /**
     * @return callable
     */
    public function getCallable(): callable
    {
        return $this->callable;
    }

    /**
     * @param callable $callable
     * @return $this
     */
    public function setCallable(callable $callable): self
    {
        $this->callable = $callable;
        return $this;
    }
}
