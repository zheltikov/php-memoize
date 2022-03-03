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
     * @return mixed
     */
    public static function memoize(?callable &$static, callable $fn, ...$arguments) // : mixed
    {
        if ($static === null) {
            $static = wrap($fn);
        }

        return $static(...$arguments);
    }

    /**
     * For implementation details,
     * @see https://wiki.php.net/rfc/static_variable_inheritance
     *
     * @param string $classname
     * @param array<class-string, callable> $static
     * @param callable $fn
     * @param mixed ...$arguments
     * @return mixed
     */
    public static function memoizeLSB(string $classname, array &$static, callable $fn, ...$arguments)
    {
        if (!array_key_exists($classname, $static)) {
            $static[$classname] = wrap($fn);
        }

        return $static[$classname](...$arguments);
    }
}
