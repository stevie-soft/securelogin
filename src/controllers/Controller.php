<?php

class Controller {
  protected array $handlers;

  public function __construct() {
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
}