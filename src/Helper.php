<?php

namespace Zheltikov\Memoize;

use function Zheltikov\Memoize\_Private\caller_to_string;
use function Zheltikov\Memoize\_Private\get_user_caller;

/**
 * TODO: remove the deprecated methods (major)
 *
 * Trait Helper
 * @package Zheltikov\Memoize
 */
trait Helper
{
    /**
     * @var \Zheltikov\Memoize\MemoizedCallable[]
     */
    protected static array $__memoize_lsb_storage__ = [];

    /**
     * @param callable $fn
     * @param ...$arguments
     * @return mixed
     * @throws \Zheltikov\Exceptions\InvariantException
     * @deprecated Use the `wrap()` function instead.
     */
    final protected static function memoize(callable $fn, ...$arguments)
    {
        return wrap($fn)->call(...$arguments);
    }

    /**
     * @param callable $fn
     * @param ...$arguments
     * @return mixed
     * @throws \Zheltikov\Exceptions\InvariantException
     */
    final protected static function memoizeLSB(
        callable $fn,
        ...$arguments
    ) {
        return static::memoizeLSBOptions($fn, null, null, ...$arguments);
    }

    /**
     * @param callable $fn
     * @param \Zheltikov\Memoize\Cache|null $cache
     * @param \Zheltikov\Memoize\KeyGenerator|null $key_generator
     * @param ...$arguments
     * @return mixed
     * @throws \Zheltikov\Exceptions\InvariantException
     */
    final protected static function memoizeLSBOptions(
        callable $fn,
        ?Cache $cache = null,
        ?KeyGenerator $key_generator = null,
        ...$arguments
    ) {
        $caller = caller_to_string(get_user_caller());
        if (array_key_exists($caller, static::$__memoize_lsb_storage__)) {
            $callable = static::$__memoize_lsb_storage__[$caller];
        } else {
            $callable = new MemoizedCallable($fn, $cache, $key_generator);
            static::$__memoize_lsb_storage__[$caller] = $callable;
        }
        return $callable->call(...$arguments);
    }
}
