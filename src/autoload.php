<?php

namespace RusaDrako\router;

$arr_load = [
	'trait__arg.php',
	'/router_core.php',
	'/router_add.php',
];

foreach($arr_load as $k => $v) {
	require_once(__DIR__ . '/' . $v);
}



require_once('aliases.php');
