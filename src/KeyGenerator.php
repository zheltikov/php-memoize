<?php

namespace Zheltikov\Memoize;

interface KeyGenerator
{
    /**
     * @param mixed ...$args
     * @return string
     */
    public function generateKey(...$args): string;
}
