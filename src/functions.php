<?php

namespace Zheltikov\Memoize;

/**
 * @param callable $fn The function to wrap into memoization.
 * @return callable The resulting wrapped memoized function.
 */
function wrap(callable $fn): callable
{
	return function () use ($fn) {
		static $cache = [];

		$args = func_get_args();

		// TODO: when switching the used hash algo, clear the cache, thus, freeing memory
		$key = hash(Config::getHashAlgo(), serialize($args));

		if (!array_key_exists($key, $cache)) {
			$cache[$key] = call_user_func_array($fn, $args);
		}

		return $cache[$key];
	};
}
