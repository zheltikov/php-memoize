<?php

namespace Zheltikov\Memoize;

use Exception;

final class Config
{
	/**
	 * @var string|null
	 */
	private static ?string $hash_algo = null;

	private function __construct()
	{
	}

	/**
	 * @return string
	 */
	public static function getHashAlgo(): string
	{
		if (static::$hash_algo === null) {
			// FIXME: the md5 hash algo may have collisions but for now, it'll suffice
			// By default we use the md5 hashing algorithm
			static::$hash_algo = 'md5';
		}

		return static::$hash_algo;
	}

	/**
	 * @param string|null $hash_algo
	 * @return void
	 * @throws \Exception
	 */
	public static function setHashAlgo(?string $hash_algo = null): void
	{
		if ($hash_algo === null) {
			static::$hash_algo = null;
			return;
		}

		if (!in_array($hash_algo, hash_algos())) {
			throw new Exception('Invalid hashing algorithm supplied: ' . var_export($hash_algo, true));
		}

		static::$hash_algo = $hash_algo;
	}
}
