--TEST--
Test that a the cache of a memoized function is cleared when the hash algo is switched.
--FILE--
<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use Zheltikov\Memoize\Config;
use function Zheltikov\Memoize\wrap;

function my_expensive_function(int $count): string
{
	sleep(2);
	return 'Hello' . str_repeat(' World', $count) . '!';
}

function timer(callable $fn): callable
{
	return function () use ($fn): float {
		$start = microtime(true);
		call_user_func_array($fn, func_get_args());
		return microtime(true) - $start;
	};
}

$inner = wrap('my_expensive_function');
$wrapped = timer($inner);
$time = $wrapped(10);
var_dump((int) $time);

$time2 = $wrapped(10);
var_dump($time2 < $time);

$inner->getKeyGenerator()->setHashAlgo('whirlpool');

$time3 = $wrapped(10);
var_dump((int) $time3);

$time4 = $wrapped(10);
var_dump($time4 < $time3);

?>
--EXPECT--
int(2)
bool(true)
int(2)
bool(true)