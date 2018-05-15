<?php

require 'vendor/autoload.php';

$config = require('config/main.global.php');

try {
    QuadxModule\Mvc\Application::init($config)->run();
} catch (\Exception $ex) {
    echo 'Execution Error: '.$ex->getMessage();
}
