<?php

use Kernel\Backend\App;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

define('APP_ROOT', dirname(__DIR__));

require_once __DIR__ . '/../vendor/autoload.php';

$app = new App();
$app->run();