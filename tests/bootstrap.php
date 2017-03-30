<?php

require_once dirname(__FILE__) . "/../vendor/autoload.php";

function dd($x) {
    array_map(function($x) { var_dump($x); }, func_get_args());
    die;
}