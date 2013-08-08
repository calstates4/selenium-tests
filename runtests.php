<?php

foreach (glob(__DIR__ ."/tests/php/*.php") as $filename) {
	exec('vendor/bin/phpunit '. $filename);
}