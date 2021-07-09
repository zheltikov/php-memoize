--TEST--
Test that a memoized function is pure.
--FILE--
<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use function Zheltikov\Memoize\wrap;

function my_function(): string
{
	global $counter;
	$counter++;
	return 'hello';
}

$counter = 0;
var_dump($counter);

my_function();
my_function();
var_dump($counter);

$memoized = wrap('my_function');
$memoized();
$memoized();
var_dump($counter);

?>
--EXPECT--
int(0)
int(2)
int(3)