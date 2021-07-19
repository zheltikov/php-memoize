<?php

namespace Zheltikov\Memoize;

use function Zheltikov\Invariant\{invariant, invariant_violation};

final class Config
{
    /**
     * @var \Zheltikov\Memoize\MemoizedCallable[]
     */
    private static array $memoized_callables = [];

    private function __construct()
    {
    }

    // -------------------------------------------------------------------------

    /**
     * @param \Zheltikov\Memoize\KeyGenerator $generator
     */
    public static function setKeyGenerators(KeyGenerator $generator): void
    {
        foreach (self::$memoized_callables as $callable) {
            $callable->setKeyGenerator($generator);
        }
    }

    /**
     * @param \Zheltikov\Memoize\Cache $cache
     */
    public static function setCaches(Cache $cache): void
    {
        foreach (self::$memoized_callables as $callable) {
            $callable->setCache($cache);
        }
    }

    public static function clearCaches(): void
    {
        foreach (self::$memoized_callables as $callable) {
            $callable->getCache()->clear();
        }
    }

    /**
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
        foreach (self::$memoized_callables as $c) {
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
        foreach (self::$memoized_callables as $key => $c) {
            if ($c === $callable) {
                unset(self::$memoized_callables[$key]);
                return;
            }
        }

        invariant_violation('Memoized callable not registered: %s', $callable);
    }
}
