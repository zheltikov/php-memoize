<?php

namespace Zheltikov\Memoize;

use function Zheltikov\Invariant\invariant_violation;

final class Config
{
    /**
     * @var \Zheltikov\Memoize\MemoizedCallable[]
     */
    private static array $memoized_callables = [];

    /**
     * @var \Zheltikov\Memoize\MemoizedCallable[]
     */
    private static array $wrap_storage = [];

    private function __construct()
    {
    }

    // -------------------------------------------------------------------------

    /**
     * @param string $key
     * @param \Zheltikov\Memoize\MemoizedCallable $callable
     */
    public static function addWrapStorage(string $key, MemoizedCallable $callable): void
    {
        self::$wrap_storage[$key] = $callable;
    }

    /**
     * @param string $key
     * @return \Zheltikov\Memoize\MemoizedCallable|null
     */
    public static function getWrapStorage(string $key): ?MemoizedCallable
    {
        return array_key_exists($key, self::$wrap_storage)
            ? self::$wrap_storage[$key]
            : null;
    }

    /**
     * FIXME: this method isn't really useful, right? :)
     * @param \Zheltikov\Memoize\KeyGenerator $generator
     */
    public static function setKeyGenerators(KeyGenerator $generator): void
    {
        foreach (self::getMemoizedCallables() as $callable) {
            $callable->setKeyGenerator($generator);
        }
    }

    /**
     * FIXME: this method isn't really useful, right? :)
     * @param \Zheltikov\Memoize\Cache $cache
     */
    public static function setCaches(Cache $cache): void
    {
        foreach (self::getMemoizedCallables() as $callable) {
            $callable->setCache($cache);
        }
    }

    /**
     * FIXME: this method isn't really useful, right? :)
     */
    public static function clearCaches(): void
    {
        foreach (self::getMemoizedCallables() as $callable) {
            $callable->getCache()->clear();
        }
    }

    /**
     * FIXME: this method isn't really useful, right? :)
     * @return \Zheltikov\Memoize\MemoizedCallable[]
     */
    public static function getMemoizedCallables(): array
    {
        return self::$memoized_callables;
    }

    // -------------------------------------------------------------------------

    /**
     * @param \Zheltikov\Memoize\MemoizedCallable $callable
     * @throws \Zheltikov\Exceptions\InvariantException
     */
    public static function registerMemoizedCallable(MemoizedCallable $callable): void
    {
        // TODO: looping over all registered callables may be slow. Optimize this. (minor)
        // TODO: maybe there is a way to uniquely identify a callable?
        // TODO: if this check is done via key indexing, it would be faster
        foreach (self::getMemoizedCallables() as $c) {
            if ($c === $callable) {
                invariant_violation('Memoized callable already registered: %s', $callable);
            }
        }

        self::$memoized_callables[] = $callable;
    }

    /**
     * @param \Zheltikov\Memoize\MemoizedCallable $callable
     * @throws \Zheltikov\Exceptions\InvariantException
     */
    public static function unregisterMemoizedCallable(MemoizedCallable $callable): void
    {
        // TODO: looping over all registered callables may be slow. Optimize this. (minor)
        foreach (self::getMemoizedCallables() as $key => $c) {
            if ($c === $callable) {
                unset(self::$memoized_callables[$key]);
                return;
            }
        }

        invariant_violation('Memoized callable not registered: %s', $callable);
    }
}
