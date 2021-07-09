--TEST--
Test the timing for a basic memoized function.
--FILE--
<?php

require_once(__DIR__ . '/../vendor/autoload.php');

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

$timed = timer('my_expensive_function');
$time = $timed(10);
var_dump((int) $time);

$memoized_timed = timer(wrap('my_expensive_function'));
$memoized_time = $memoized_timed(10);
var_dump((int) $memoized_time);
$memoized_time = $memoized_timed(10);
var_dump($memoized_time < $time);

?>
--EXPECT--
int(2)
int(2)
bool(true)