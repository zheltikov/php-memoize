<?php

namespace Zheltikov\Memoize;

use function Zheltikov\Invariant\{invariant, invariant_violation};

final class Config
{
	/**
	 * @var string|null
	 */
	private static ?string $hash_algo = null;

    /**
     * @var \Zheltikov\Memoize\MemoizedCallable[]
     */
    private static array $memoized_callables = [];

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
			self::setHashAlgoForMemoizedCallables();
			return;
		}

		invariant(
		    in_array($hash_algo, hash_algos(), true),
            'Supplied value %s must be valid hashing algorithm',
            $hash_algo
        );

		self::$hash_algo = $hash_algo;
        self::setHashAlgoForMemoizedCallables();
	}

    /**
     * @param \Zheltikov\Memoize\MemoizedCallable $callable
     * @throws \Zheltikov\Exceptions\InvariantException
     */
    public static function registerMemoizedCallable(MemoizedCallable $callable): void
    {
        foreach (self::$memoized_callables as $c) {
            if ($c === $callable) {
                invariant_violation('Memoized callable already registered: %s', $callable);
            }
        }

        self::$memoized_callables[] = $callable;
    }

    /**
     * @param \Zheltikov\Memoize\MemoizedCallable $callable
     * @throws \Zheltikov\Exceptions\InvariantException
     */
    public static function unregisterMemoizedCallable(MemoizedCallable $callable): void
    {
        foreach (self::$memoized_callables as $key => $c) {
            if ($c === $callable) {
                unset(self::$memoized_callables[$key]);
                return;
            }
        }

        invariant_violation('Memoized callable not registered: %s', $callable);
    }

    private static function setHashAlgoForMemoizedCallables(): void
    {
        foreach (self::$memoized_callables as $callable) {
            $callable->setHashAlgo(self::getHashAlgo());
        }
    }

    private static function clearCacheForMemoizedCallables(): void
    {
        foreach (self::$memoized_callables as $callable) {
            $callable->clearCache();
        }
    }
}
