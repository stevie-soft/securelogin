<?php

class LoginController extends Controller {

  public function setup() {
    $this->handlers["GET"] = [$this, 'showLoginPage'];
    $this->handlers["POST"] = [$this, 'doLogin'];
  }

  protected function showLoginPage() {
    if ($_SESSION["authenticated"]) {
      Router::redirect("/profile");
    }

    View::render("login");
  }

  protected function doLogin() {
    global $config;

    $request = new LoginRequestDto($_POST);
    $request->validate();

    $credentials = new CredentialsManager(
         __DIR__ . $config->getPasswordsFilePath(),
      $config->getDecryptionKey()
    );
    $credentials->loadPasswords();

    $isUsernameValid = $credentials->userExists($request->username);
    if (!$isUsernameValid) {
      error_log("Could not find user '{$request->username}' in the registered credentials. ");
      $this->fail(ErrorCode::BAD_USERNAME_ERROR);
    }

    $isPasswordValid = $credentials->verifyPassword($request->username, $request->password);
    if (!$isPasswordValid) {
      error_log("The registered and given passwords do not mach. ");
      $this->fail(ErrorCode::BAD_PASSWORD_ERROR);
    }

    $user = User::findByUsername($request->username);
    if (!$user) {
      error_log("Could not find user '{$request->username}' in the database. ");
      $this->fail(ErrorCode::UNEXPECTED_ERROR);
    }

    $_SESSION["username"] = $user->username;
    $_SESSION["authenticated"] = true;

    Router::redirect("/profile");
  }
}