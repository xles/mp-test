<?php
#header('Content-Type: text/plain');


function get_args($args, $defaults)
{
	if (count($args) > count($defaults)) {
		$argsc = count($args);
		$defaultsc = count($defaults);
		$t = debug_backtrace();
		$f = "'{$t[1]['function']}'";
		if (isset($t[1]['class']))
			$f = "method $f in '{$t[1]['class']}'";
		else 
			$f = "function $f";
		trigger_error(
			"Passing too many paramterers to $f; ".
			"saw $argsc, expected $defaultsc", 
		E_USER_ERROR);
	}
	foreach ($args as $key => $val) {
		if (!in_array($key, array_keys($defaults))) {
			$t = debug_backtrace();
			$f = "'{$t[1]['function']}'";
			if (isset($t[1]['class']))
				$f = "method $f in '{$t[1]['class']}'";
			else 
				$f = "function $f";
			trigger_error(
				"No such paramterer '$key' in $f", 
			E_USER_ERROR);
		}
	}
	$params = [];
	foreach ($defaults as $key => $val) {
		if ($val == 'REQUIRED' && !isset($args[$key])) {
			$t = debug_backtrace();
			$f = "'{$t[1]['function']}'";
			if (isset($t[1]['class']))
				$f = "method $f in '{$t[1]['class']}'";
			else 
				$f = "function $f";
			trigger_error(
				"Invalid value for '$key' in $f", 
			E_USER_ERROR);			
		}
		if (isset($args[$key])) {
			$params[$key] = $args[$key];
		} else {
			$params[$key] = $defaults[$key];
		}
	}
	return $params;
}

class Param {
	public function test($args = []) { extract(get_args($args,[
	'foo' => 'bananas',
	'bar' => 'bananas',
	'baz' => 'bananas'
	]));


		function_using($foo, $bar, $baz);
	}
}

function function_using($a, $b, $c)
{
	echo "a: '$a'\nb: '$b'\nc: '$c'\n";
}

$param = new Param();

$param->test(['foo' => 'bar']);
