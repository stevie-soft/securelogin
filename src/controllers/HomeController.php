<?php

class HomeController extends Controller {
  public function setup() {
    $this->handlers["GET"] = [$this, 'automaticRedirect'];
  }

  public function automaticRedirect() {
    
  }
}