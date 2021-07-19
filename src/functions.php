<?php

namespace Zheltikov\Memoize;

/**
 * @param callable $fn The function to wrap into memoization.
 * @param Cache|null $cache The cache storage to use.
 * @param KeyGenerator|null $key_generator The key generator to use when
 *        serializing arguments.
 * @return MemoizedCallable The resulting wrapped memoized function.
 * @throws \Zheltikov\Exceptions\InvariantException
 */
function wrap(callable $fn, ?Cache $cache = null, ?KeyGenerator $key_generator = null): MemoizedCallable
{
    return new MemoizedCallable($fn, $cache, $key_generator);
}
