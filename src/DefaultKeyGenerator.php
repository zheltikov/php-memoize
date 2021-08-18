<?php

namespace Zheltikov\Memoize;

use function Zheltikov\Invariant\invariant;

/**
 * This is the default key generator implementation.
 *
 * It is very simple, and suitable for most situations.
 * It just serializes the arguments and hashes the result.
 * But consider this an implementation detail.
 *
 * If you need some other custom key generation logic, you can always implement your own!
 *
 * Class DefaultKeyGenerator
 * @package Zheltikov\Memoize
 */
class DefaultKeyGenerator implements KeyGenerator
{
    /**
     * @var string
     */
    private string $hash_algo = 'md5';

    /**
     * @param mixed ...$args
     * @return string
     */
    public function generateKey(...$args): string
    {
        return hash($this->getHashAlgo(), serialize($args));
    }

    /**
     * @return string
     */
    public function getHashAlgo(): string
    {
        return $this->hash_algo;
    }

    /**
     * @param string $hash_algo
     * @return $this
     * @throws \Zheltikov\Exceptions\InvariantException
     */
    public function setHashAlgo(string $hash_algo): self
    {
        invariant(
            in_array($hash_algo, hash_algos(), true),
            'Supplied value %s must be valid hashing algorithm',
            $hash_algo
        );

        $this->hash_algo = $hash_algo;
        return $this;
    }
}
