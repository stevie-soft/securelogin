<?php

class ErrorController extends Controller {

  public function setup() {
    $this->handlers["GET"] = [$this, "showErrorPage"];
  }

  public function showErrorPage() {
    View::render("error", [
      "ERROR_CODE" => $_GET["error"]
    ]);
  }
}