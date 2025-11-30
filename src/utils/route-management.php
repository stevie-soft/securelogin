<?php

class Router {
  /** @var array<string, class-string<Controller>> */
  private array $routes;

  /**
     * @param array<string, class-string<Controller>> $routes
     */
  public function __construct(array $routes = []) {
    $this->routes = $routes;
  }

  public function handle(string $path, string $method) {
    $Controller = $this->routes[$path];

    if (!$Controller) {
      http_response_code(404);
      echo "Not Found";
      exit();
    }

    $controller = new $Controller();
    $controller->handle($method);
  }

  public static function redirect(string $targetPath) {
    header("Location: /{$targetPath}");
    exit();
  }
}
