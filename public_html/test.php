<?php
/**
 * Argument parser for faking named paramters.
 *
 * To use this parser functions need to be declared like so:
 *
 * function method($args = []) { extract(get_args($args,[
 * 		'foo' => 'default string',
 * 		'bar' => 12345,
 * 		'baz' => true
 * 	]));
 * 
 * @param  array $args     Argument list passed to function.
 * @param  array $defaults Default values for arguments.
 * @return array           The parsed array of paramters for extract.
 */
function get_args($args, $defaults)
{
	/*  Are we supplying more arguments than needed?  */
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

	/*  Get the parameter names.  */
	$dparams = [];
	foreach($defaults as $key => $val) {
		if (is_numeric($key))
			$dparams[] = $val;
		else 
			$dparams[] = $key;
	}

	/*  Does the parameter for the given argument even exist?  */
	foreach ($args as $key => $val) {
		if (!in_array($key, $dparams)) {
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

	/*  Splice in default values if no parameter value passed.  */
	$params = [];
	foreach ($defaults as $key => $val) {
		/* If there's no default value, 
		   it's a required parameter. */
		if (is_numeric($key) && !isset($args[$val])) {
			$t = debug_backtrace();
			$f = "'{$t[1]['function']}'";
			if (isset($t[1]['class']))
				$f = "method $f in '{$t[1]['class']}'";
			else 
				$f = "function $f";
			trigger_error(
				"Invalid value for '$val' in $f", 
			E_USER_ERROR);			
		}
		if (is_numeric($key)) {
			$key = $val;
		}

		/* If an argument is supplied, use it. */
		if (isset($args[$key])) {
			$params[$key] = $args[$key];
		} else {
			$params[$key] = $defaults[$key];
		}
	}
	return $params;
}

class Param {
	public function test($args = []) 
	{ 
		extract(get_args($args,['foo' => 'bananas',
					'bar',
					'baz' => 'bananas']));
		
		$this->function_using($foo, $bar, $baz);
	}


	private function function_using($a, $b, $c)
	{
		echo "a: '$a'\nb: '$b'\nc: '$c'\n";
	}
}

$param = new Param();

$param->test(['foo' => 'fubar',
	      'bar' => 'moot']);
