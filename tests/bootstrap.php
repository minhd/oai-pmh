<?php

// autoloading files
require_once dirname(__FILE__) . "/../vendor/autoload.php";

// bypass warning when timezone is not set properly
date_default_timezone_set('UTC');

// debug functions
function dd($x) {
    array_map(function($x) { var_dump($x); }, func_get_args());
    die;
}