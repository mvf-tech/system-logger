#!/usr/bin/env php
<?php

$captures = json_decode(file_get_contents("/captures.json"));

$vars = [
    (object)[ 'env' => 'CLASS', 'coverage' => 'classes', 'text' => 'Class'],
    (object)[ 'env' => 'METHOD', 'coverage' => 'methods', 'text' => 'Method'],
    (object)[ 'env' => 'LINE', 'coverage' => 'lines', 'text' => 'Line'],
];

foreach ($vars as $var) {
    $max = getenv($var->env . "_COVERAGE_MAX");
    $min = getenv($var->env . '_COVERAGE_MIN');
    $coverage = $captures->{$var->coverage};
    if ($min >= $coverage || $coverage > $max) {
        echo "$var->text coverage is not within acceptable bounds; $min < $coverage <= $max" . PHP_EOL;
        exit(1);
    } else {
        echo "$var->text coverage within acceptable bounds; $min < $coverage <= $max" . PHP_EOL;
    }
}
