<?php
require_once '../src/bootstrap.php';

$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$method = $_SERVER['REQUEST_METHOD'];
$router = new Router([
  '' => HomeController::class,
  'login' => LoginController::class,
  'profile' => ProfileController::class,
]);
$router->handle($path, $method);