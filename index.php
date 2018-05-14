<?php

require 'vendor/autoload.php';

$config = require('config/main.global.php');

(new QuadxModule\Mvc\Application($config))->run();
