<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../routes/web.php';
require_once __DIR__ . '/../config/config.php';



use Core\Router;

$url = $_GET['url'] ?? '/';
Router::dispatch($url);
