<?php

class EnvVarManager {

  public function set(string $key, string $value) {
    $_ENV[$key] = $value;
  }

  public function get(string $key): string {
    return $_ENV[$key];
  }

  public function loadDotEnv(string $filePath = ".env") {
    $lines = file($filePath);
    
    if (!$lines) {
      die("Could not load env file at '" + $filePath + "'");
    }

    foreach ($lines as $line) {
      [$key, $value] = explode('=', $line, 2);
      $key = trim($key);
      $value = trim($value);

      $this->set($key, $value);
    }
  }

}

class Config {
  private EnvVarManager $envVarManager;

  public function __construct() {
    $this->envVarManager = new EnvVarManager();
  }

  public function loadDotEnv() {
    $this->envVarManager->loadDotEnv();
  }

  public function getPasswordsFilePath(): string {
    return "./password.txt";
  }

  public function getDecryptionKey(): array {
    $keyAsString = $this->envVarManager->get("DECRYPTION_KEY");

    return array_map(
      callback: 'intval', 
      array: explode(',', $keyAsString)
    );
  }

  public function getDatabaseCredentials(): array {
    $username = $this->envVarManager->get("DB_USERNAME");
    $password = $this->envVarManager->get("DB_PASSWORD");

    return [$username, $password];
  }
}

class LoginRequest {
  public string $username;
  public string $password;

  public function __construct(array $data) {
    $this->username = $data["username"];
    $this->password = $data["password"];
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

  public function __construct(string $passwordsFilePath, array $key) {
    $this->passwordsFilePath = $passwordsFilePath;
    $this->key = $key;
    $this->db = [];
  }

  public function loadPasswords() {
    $passwordFileLines = file($this->passwordsFilePath);

    if (!$passwordFileLines) {
      die("Could not load passwords file at '" + $this->passwordsFilePath + "'");
    }

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

$request = new LoginRequest($_POST);
$request->validate();

$config = new Config();
$config->loadDotEnv();

$credentialsManager = new CredentialsManager(
  passwordsFilePath: $config->getPasswordsFilePath(), 
  key: $config->getDecryptionKey()
);
$credentialsManager->loadPasswords();

$validUser = $credentialsManager->userExists($request->username);
if (!$validUser) {
  die("Uknown user!");
}

$validPassword = $credentialsManager->verifyPassword($request->username, $request->password);
if (!$validPassword) {
  die("Invalid password!");
}
?>