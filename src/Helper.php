<?php

namespace Zheltikov\Memoize;

/**
 * TODO: remove, the technically-required, `static` argument to all these methods, this way its usage is nicer :)
 *
 * TODO: first, add the new nice methods (minor)
 * TODO: second, mark the old methods as deprecated (minor)
 * TODO: third, alias the old methods to the new ones; only if technically possible (minor)
 * TODO: fourth, remove the old methods (major)
 *
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
