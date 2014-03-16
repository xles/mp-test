<?php
header('Content-Type: text/plain');


function get_args($args, $defaults)
{
	// Do a bunch of checking and error handling here.
	// 
	return $defaults;
}

function paramtest($args = []) 
{
	extract(get_args($args,[
		'foo' => 'bananas',
		'bar' => 'bananas',
		'baz' => 'bananas'
	]));

	function_using($foo, $bar, $baz);
}

function function_using($a, $b, $c)
{
	echo "a: '$a'\nb: '$b'\nc: '$c'\n";
}

paramtest();
