--TEST--
Test that a memoized function is memoized.
--FILE--
<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use function Zheltikov\Memoize\wrap;

function my_function(string $data): string
{
	return md5($data);
}

$result1 = my_function('hello');
$result2 = my_function('hello');
var_dump($result1 === $result2);

$memoized = wrap('my_function');
$result3 = $memoized('world');
$result4 = $memoized('world');
var_dump($result3 === $result4);

var_dump(my_function('hello') === $memoized('hello'));
var_dump(my_function('world') === $memoized('world'));

?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)