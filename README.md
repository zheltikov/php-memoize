
# php-memoize

A PHP library for simple [memoization](https://en.wikipedia.org/wiki/Memoization) of `callable`s.

## Installation

This is a composer package, so you can install it via:

```shell
$ composer require zheltikov/php-memoize
```

## Usage

To apply memoization to a `callable`, simply call the `wrap` function to get a new, wrapped version of your `callable`. For example:

```php
<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use function Zheltikov\Memoize\wrap;

function my_expensive_function(string $who): string
{
    // Just for illustrative purposes we just sleep here,
    // but here could be a database fetch, or an expensive
    // computation.
    sleep(10);
    return 'Hello ' . $who . '!';
}

my_expensive_function('World'); // Runs in 2 seconds

$memoized = wrap('my_expensive_function');

$memoized('World'); // 2 seconds
$memoized('World'); // milliseconds

$memoized('Everyone'); // 2 seconds
$memoized('Everyone'); // milliseconds

```

As you can see, the memoized version of the function performs the computation for a given input only once, and then caches the result for subsequent calls with the same input.

Internally, the memoized function serializes and hashes the input parameters passed. By default, it uses the `md5` algorithm for hashing. In most cases, the `md5` algorithm will suffice, but if your memoization cache gets big, using it may lead to hash collisions. Because of this you are able to configure which hashing algorithm will be used for **all** memoized functions.

This configuration is done like so:

```php
<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use Zheltikov\Memoize\Config;

// Get the currently used hash algo, by default its `md5`
Config::getHashAlgo();

// Let's set the hash algo to something with less collisions
Config::setHashAlgo('whirlpool');

// We can reset the hash algo to the default
Config::setHashAlgo(null);

// If we try to set the hash algo to some algorithm that is
// not in the output of `hash_algos`, an Exception is thrown
Config::setHashAlgo('this_algo_does_not_exist');

```

Changing the used algorithm will clear the cache of all already existing memoized functions.

## TODO

- [x] Wrapper function
- [x] Configurable hashing algorithm for cache
- [ ] Configurable serialization algorithm for cache
- [ ] Configurable TTL (time-to-live) functionality
- [ ] Instance Memoization Trait
- [ ] Static Memoization Trait
- [ ] In-function wrapper interface
- [ ] Hard-reset of all or specific caches
- [ ] Inter-process caching in Redis
- [ ] Inter-process caching in MySQL/MariaDB
