<?php

class ProfileController extends Controller {

  public function setup() {
    $this->handlers["GET"] = [$this, "showProfilePage"];
  }

  private function showProfilePage() {
    if (!$_SESSION["authenticated"]) {
      Router::redirect("/login");
    }

    $user = User::findByUsername($_SESSION["username"]);

    View::render("profile", [
      'USERNAME' => $user->username,
      'FAVORITE_COLOR' => $user->favoriteColor
    ]);
  }
}