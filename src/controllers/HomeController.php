<?php

class HomeController extends Controller {
  public function setup() {
    $this->handlers["GET"] = [$this, 'automaticRedirect'];
  }

  protected function automaticRedirect() {
    if ($_SESSION["authenticated"]) {
      Router::redirect("/profile");
    } else {
      Router::redirect("/login");
    }
  }
}