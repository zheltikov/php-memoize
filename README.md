# php-memoize

A PHP library for simple [memoization](https://en.wikipedia.org/wiki/Memoization) of `callable`s.

## Installation

This is a composer package, so you can install it via:

```shell
$ composer require zheltikov/php-memoize
```

## Usage

To apply memoization to a `callable`, simply call the `wrap` function to get a new, wrapped version of your `callable`.
For example:

```php
<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use function Zheltikov\Memoize\wrap;

function my_expensive_function(string $who): string
{
    // Just for illustrative purposes we just sleep here,
    // but here could be a database fetch, or an expensive
    // computation.
    sleep(5);
    return 'Hello ' . $who . '!';
}

my_expensive_function('World'); // Runs in 5 seconds

$memoized = wrap('my_expensive_function');

$memoized('World'); // 5 seconds
$memoized('World'); // milliseconds

$memoized('Everyone'); // 5 seconds
$memoized('Everyone'); // milliseconds

```

As you can see, the memoized version of the function performs the computation for a given input only once, and then
caches the result for subsequent calls with the same input.

Internally (and by default), the memoized function serializes and hashes the input parameters passed. By default, it
uses the `md5` algorithm for hashing. In most cases, the `md5` algorithm will suffice, but if your memoization cache
gets big, using it may lead to hash collisions. Because of this you are able to configure which hashing algorithm will
be used for memoized functions.

This configuration is done like so:

```php
<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use Zheltikov\Memoize\{Config, DefaultKeyGenerator};
use function Zheltikov\Memoize\wrap;

// You can change the hashing algorithm for a specific function like so:
$wrapped = wrap(function () { /* ... */ });
$wrapped->getKeyGenerator()->setHashAlgo('whirlpool');

// Or you can change it for all previously created functions:
Config::setKeyGenerators(
    (new DefaultKeyGenerator())
        ->setHashAlgo('sha256')
);

```

## TODO

- [x] Wrapper function
- [x] Configurable hashing algorithm for cache
- [X] Class Method memoization helper function
- [X] Class Method memoization helper function with LSB (Late Static Binding)
- [X] Configurable serialization algorithm for cache
- [ ] Configurable TTL (time-to-live) functionality
- [X] Instance Memoization Trait
- [X] Static Memoization Trait
- [ ] In-function wrapper interface
- [X] Hard-reset of all or specific caches
- [X] Inter-process caching in Redis. See: <https://github.com/zheltikov/php-memoize-redis>
- [ ] Inter-process caching in MySQL/MariaDB
