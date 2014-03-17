<?php
/*
header('Content-Type: text/plain');

$path  = get_include_path();
$path .= PATH_SEPARATOR.realpath('../mp');

set_include_path($path);
echo get_include_path();

include('core.php');

var_dump(get_included_files());

*/

//namespace Foo;

function bar() {
	echo 'foo';
}

namespace Bananas;

\bar();
