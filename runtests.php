<?php

foreach (glob(__DIR__ ."/tests/php/*.php") as $filename) {
	$output = array();
	exec('vendor/bin/phpunit '. $filename, $output);
	print implode("\n", $output);
}