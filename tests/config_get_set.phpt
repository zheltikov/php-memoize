--TEST--
Test that the Config class works as expected.
--FILE--
<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use Zheltikov\Memoize\Config;

$initial = Config::getHashAlgo();
var_dump($initial);

Config::setHashAlgo('sha256');
var_dump(Config::getHashAlgo());

Config::setHashAlgo(null);
var_dump(Config::getHashAlgo() === $initial);

try {
	Config::setHashAlgo('this_hash_algo_does_not_exist');
} catch (Exception $e) {
	var_dump('invalid');
}

var_dump(Config::getHashAlgo() === $initial);

?>
--EXPECT--
string(3) "md5"
string(6) "sha256"
bool(true)
string(7) "invalid"
bool(true)