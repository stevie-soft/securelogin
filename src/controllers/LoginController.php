<?php

class LoginController extends Controller {

  public function setup() {
    $this->handlers["GET"] = [$this, 'showLoginPage'];
    $this->handlers["POST"] = [$this, 'doLogin'];
  }

  private function showLoginPage() {
    if ($_SESSION["authenticated"]) {
      Router::redirect("/profile");
    }

    View::render("login");
  }

  private function doLogin() {
    global $config;

    $request = new LoginRequestDto($_POST);
    $request->validate();

    $credentials = new CredentialsManager(
        $config->getPasswordsFilePath(),
      $config->getDecryptionKey()
    );

    $isUsernameValid = $credentials->userExists($request->username);
    if (!$isUsernameValid) {
      fail(
        ErrorCode::BAD_USERNAME_ERROR,
        "Could not find user '{$request->username}' in the registered credentials. "
      );
    }

    $isPasswordValid = $credentials->verifyPassword($request->username, $request->password);
    if (!$isPasswordValid) {
      fail(
        ErrorCode::BAD_PASSWORD_ERROR,
        "The registered and given passwords do not mach. ",
      );
    }

    $user = User::findByUsername($request->username);
    if (!$user) {
      fail(
        ErrorCode::UNEXPECTED_ERROR,
        "Could not find user '{$request->username}' in the database. "
      );
    }

    $_SESSION["username"] = $user->username;
    $_SESSION["authenticated"] = true;

    Router::redirect("/profile");
  }
}