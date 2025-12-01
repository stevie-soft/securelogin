<?php

class LogoutController extends Controller {
  public function setup() {
    $this->handlers["POST"] = [$this, "doLogout"];
  }

  protected function doLogout() {
    $_SESSION["username"] = null;
    $_SESSION["authenticated"] = false;
    Router::redirect("/login");
  }
}