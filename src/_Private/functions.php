<?php

namespace Zheltikov\Memoize\_Private;

/**
 * @return array
 */
function get_caller_stack(): array
{
    return debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
}

/**
 * @param array $stack
 * @param callable $callback
 * @return array
 */
function filter_caller_stack(array $stack, callable $callback): array
{
    $result = [];

    foreach ($stack as $frame) {
        if ($callback($frame)) {
            $result[] = $frame;
        }
    }

    return $result;
}

/**
 * @return array|null
 */
function get_user_caller(): ?array
{
    $prefix = 'Zheltikov\\Memoize\\';

    $filtered = filter_caller_stack(
        get_caller_stack(),
        function (array $frame) use ($prefix): bool {
            if (strpos($frame['function'], $prefix) === 0) {
                return false;
            }

            if (array_key_exists('class', $frame)) {
                if (strpos($frame['class'], $prefix) === 0) {
                    return false;
                }
            }

            return true;
        }
    );

    foreach ($filtered as $frame) {
        return $frame;
    }

    return null;
}

/**
 * @param array|null $caller
 * @return string
 */
function caller_to_string(?array $caller): string
{
    if ($caller === null) {
        return '{main}';
    }

    $string = '';

    if (array_key_exists('class', $caller)) {
        $string .= $caller['class'];
    }

    if (array_key_exists('type', $caller)) {
        $string .= $caller['type'];
    }

    if (array_key_exists('function', $caller)) {
        $string .= $caller['function'];
    }

    return $string;
}
