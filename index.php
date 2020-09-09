<?php

use App\Handler;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/constants.php';

$handler = new Handler();
$handler->start();
$handler->end();