<?php
/**
 * Argument parser for faking named paramters.
 *
 * To use this parser functions need to be declared like so:
 *
 * function test($args = []) { extract(get_args($args,[
 * 'foo' => 'default string',
 * 'bar' => 12345,
 * 'baz' => true
 * ]));
 * 
 * @param  array $args     Argument list passed to function.
 * @param  array $defaults Default values for arguments.
 * @return array           The parsed array of paramters for extract.
 */
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
	'bar' => 'REQUIRED',
	'baz' => 'bananas'
	]));
		$this->function_using($foo, $bar, $baz);
	}


	private function function_using($a, $b, $c)
	{
		echo "a: '$a'\nb: '$b'\nc: '$c'\n";
	}
}


$param = new Param();

$param->test(['foo' => 'bar']);
