<?php

namespace Zheltikov\Memoize;

use function Zheltikov\Invariant\invariant;

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
		if (self::$hash_algo === null) {
			// FIXME: the md5 hash algo may have collisions but for now, it'll suffice
			// By default we use the md5 hashing algorithm
			self::$hash_algo = 'md5';
		}

		return self::$hash_algo;
	}

    /**
     * @param string|null $hash_algo
     * @return void
     * @throws \Zheltikov\Exceptions\InvariantException
     */
	public static function setHashAlgo(?string $hash_algo = null): void
	{
		if ($hash_algo === null) {
			self::$hash_algo = null;
			return;
		}

		invariant(
		    in_array($hash_algo, hash_algos(), true),
            'Supplied value %s must be valid hashing algorithm',
            $hash_algo
        );

		self::$hash_algo = $hash_algo;
	}
}
