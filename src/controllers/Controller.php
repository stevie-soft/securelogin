<?php

class Controller {

  protected string $path;
  protected array $handlers;

  public function __construct(string $path) {
    $this->path = $path;
    $this->handlers = [];
    $this->setup();
  }

  protected function setup() {
    die('Setting up controller without any mapped handlers. ');
  }

  public function handle(string $method) {
    $handler = $this->handlers[$method];

    if (!$handler) {
      die("Method '{$method}' not implemented. ");
    }

    $handler();
  }

  protected function fail(ErrorCode $errorCode) {
    $path = "/{$this->path}?error={$errorCode->value}";
    Router::redirect($path);
  }
}