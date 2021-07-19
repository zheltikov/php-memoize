<?php

namespace Zheltikov\Memoize;

/**
 * Trait Helper
 * @package Zheltikov\Memoize
 */
trait Helper
{
    /**
     * @param MemoizedCallable|null $static
     * @param callable $fn
     * @param ...$arguments
     * @return mixed
     * @throws \Zheltikov\Exceptions\InvariantException
     */
    public static function memoize(?MemoizedCallable &$static, callable $fn, ...$arguments)
    {
        if ($static === null) {
            $static = wrap($fn);
        }

        return $static->call(...$arguments);
    }

    /**
     * @param string $classname
     * @param MemoizedCallable|null $static
     * @param callable $fn
     * @param ...$arguments
     * @return mixed
     * @throws \Zheltikov\Exceptions\InvariantException
     */
    public static function memoizeLSB(
        string $classname,
        ?MemoizedCallable &$static,
        callable $fn,
        ...$arguments
    ) {
        if ($static === null) {
            $static = wrap(
                function () use ($fn): MemoizedCallable {
                    /** @var MemoizedCallable|null $fn2 */
                    static $fn2 = null;

                    if ($fn2 === null) {
                        $fn2 = wrap($fn);
                    }

                    return $fn2;
                }
            );
        }

        return $static->call($classname)->call(...$arguments);
    }
}
