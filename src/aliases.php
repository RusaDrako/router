<?php

if (class_exists('RD_Router', false)) { return; }

$classMap = [
	'RusaDrako\\router\\router_core'      => 'RD_Router_Core',
	'RusaDrako\\router\\router_add'       => 'RD_Router',
];

foreach ($classMap as $class => $alias) {
	class_alias($class, $alias);
}
