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
		static $hash_algo = null;

		if ($hash_algo === null) {
			// Initialize the hash algo
			$hash_algo = Config::getHashAlgo();
		} else {
			// when switching the used hash algo, clear the cache, thus, freeing memory
			// The hash algo has changed
			if ($hash_algo !== Config::getHashAlgo()) {
				// Clear the cache
				$cache = [];
				$hash_algo = Config::getHashAlgo();
			}
		}

		$args = func_get_args();
		$key = hash($hash_algo, serialize($args));

		// There is no such parameter combo in the cache, compute the result and cache it
		if (!array_key_exists($key, $cache)) {
			$cache[$key] = call_user_func_array($fn, $args);
		}

		return $cache[$key];
	};
}
