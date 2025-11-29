<?php

class LoginRequest {
  public string $username;
  public string $password;

  public function __construct(string $username, string $password) {
    $this->username = $username;
    $this->password = $password;
  }

  public function isValid(): bool {
    return isset($this->username) && isset($this->password);
  }

  public function validate() {
    if (!$this->isValid()) {
      die("Invalid request!");
    }
  }
}

class Credentials {
  private string $entry;
  public string $username;
  public string $password;

  public function __construct(string $entry) {
    $this->entry = $entry;
  }

  public function decrypt(array $key) {
    // TODO: implement decryption
  }
}

class CredentialsManager {
  private string $passwordsFilePath;
  private string $keyFilePath;

  private array $key;

  private array $db;

  public function __construct(string $passwordsFilePath, string $keyFilePath) {
    $this->passwordsFilePath = $passwordsFilePath;
    $this->keyFilePath = $keyFilePath;
    $this->key = [];
    $this->db = [];
  }

  public function load() {
    $passwordFileLines = file($this->passwordsFilePath);

    if (!$passwordFileLines) {
      die("Could not load passwords file at '" + $this->passwordsFilePath + "'");
    }

    $keyFileLines = file($this->keyFilePath);

    if (!$keyFileLines) {
      die("Could not load key file at '" + $this->keyFilePath + "'");
    }

    $containsKey = count($keyFileLines) > 0;

    if (!$containsKey) {
      die("Could not find key in key file at '" + $this->keyFilePath + "'");
    }

    $this->key = array_map('intval', explode(',', $keyFileLines[0]));

    foreach ($passwordFileLines as $entry) {
      $credentials = new Credentials($entry);
      $credentials->decrypt($this->key);
      $this->db[$credentials->username] = $credentials->password;
    }
  }

  public function userExists(string $username): bool {
    return array_key_exists($username, $this->db);
  }

  public function verifyPassword(string $username, string $password): bool {
    return $this->db[$username] === $password;
  }
}

$USERNAME_FIELD_KEY = "username";
$PASSWORD_FIELD_KEY = "password";
$PASSWORDS_FILE_PATH = "./password.txt";
$KEY_FILE_PATH = "./key.txt";

$request = new LoginRequest($_POST[$USERNAME_FIELD_KEY], $_POST[$PASSWORD_FIELD_KEY]);
$request->validate();

$credentialsManager = new CredentialsManager($PASSWORDS_FILE_PATH , $KEY_FILE_PATH);
$credentialsManager->load();

$validUser = $credentialsManager->userExists($request->username);
if (!$validUser) {
  die("Uknown user!");
}

$validPassword = $credentialsManager->verifyPassword($request->username, $request->password);
if (!$validPassword) {
  die("Invalid password!");
}
?>