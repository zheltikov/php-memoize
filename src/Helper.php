<?php

namespace Zheltikov\Memoize;

/**
 * Trait Helper
 * @package Zheltikov\Memoize
 */
trait Helper
{
    /**
     * @param callable|null $static
     * @param callable $fn
     * @param ...$arguments
     * @return callable
     */
    public static function memoize(?callable &$static, callable $fn, ...$arguments): callable
    {
        if ($static === null) {
            $static = wrap($fn);
        }

        return $static(...$arguments);
    }

    /**
     * @param string $classname
     * @param callable|null $static
     * @param callable $fn
     * @param ...$arguments
     * @return callable
     */
    public static function memoizeLSB(string $classname, ?callable &$static, callable $fn, ...$arguments): callable
    {
        if ($static === null) {
            $static = wrap(
                function () use ($fn): callable {
                    /** @var callable|null $fn2 */
                    static $fn2 = null;

                    if ($fn2 === null) {
                        $fn2 = wrap($fn);
                    }

                    return $fn2;
                }
            );
        }

        return $static($classname)(...$arguments);
    }
}
