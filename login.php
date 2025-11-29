<?php

class EnvVarManager {

  public function set(string $key, string $value) {
    $_ENV[$key] = $value;
  }

  public function get(string $key): string {
    return $_ENV[$key];
  }

  public function loadDotEnv(string $filePath = ".env") {
    $lines = file($filePath, FILE_IGNORE_NEW_LINES);
    
    if (!$lines) {
      die("Could not load env file at '{$filePath}'");
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

  public function getDatabaseHost(): string {
    return "localhost";
  }

  public function getDatabaseUsername(): string {
    return $this->envVarManager->get("DB_USERNAME");
  }

  public function getDatabasePassword(): string {
    return $this->envVarManager->get("DB_PASSWORD");
  }

  public function getDatabaseName(): string {
    return "xadatok";
  }
}

class LoginRequest {
  public string | null $username;
  public string | null $password;

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
    $decryptedEntry = "";
    $keyIndex = 0;
    $maxKeyIndex = count($key) - 1;

    for ($i = 0; $i < strlen($this->entry); $i++) {
        $char = ord($this->entry[$i]);
        $decryptedChar = $char - $key[$keyIndex];
        $decryptedEntry .= chr($decryptedChar);

        $keyIndex++;
        if ($keyIndex > $maxKeyIndex) {
          $keyIndex = 0;
        }
    }

    [$decryptedUsername, $decryptedPassword] = explode('*', $decryptedEntry, 2);
    $this->username = $decryptedUsername;
    $this->password = $decryptedPassword;
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
    $passwordFileLines = file($this->passwordsFilePath, FILE_IGNORE_NEW_LINES);

    if (!$passwordFileLines) {
      die("Could not load passwords file at '{$this->passwordsFilePath}'");
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

class DatabaseManager {
  private string $host;
  private string $username;
  private string $password;
  private string $databaseName;
  private mysqli | null $connection;

  public function __construct(string $host, string $username, string $password, string $databaseName) {
    $this->host = $host;
    $this->username = $username;
    $this->password = $password;
    $this->databaseName = $databaseName;
    $this->connection = null;
  }

  public function connect() {
    $this->connection = new mysqli(
      hostname: $this->host, 
      username: $this->username, 
      password: $this->password, 
      database: $this->databaseName
    );

    if ($this->connection->connect_error) {
      die("Could not connect to MySQL database '{$this->databaseName}' at '{$this->host}' with user '{$this->username}'");
    }
  }

  public function query(string $statementTemplate, string $paramTypes, string ...$params): array {
    $statement = $this->connection->prepare($statementTemplate);
    $statement->bind_param($paramTypes, $params);
    $statement->execute();

    $result = $statement->get_result();
    return $result->fetch_all(MYSQL_ASSOC);
  }
}

class User {
  public int $id;
  public string $username;
  public string $favoriteColor;

  public function __construct(array $values) {
    $this->id = $values["Sor"];
    $this->username = $values["Username"];
    $this->favoriteColor = $values["Titkos"];
  }
}

class AdatokDatabaseManager extends DatabaseManager {
  public function findUserByUsername(string $username): User | null {
    $statement = "SELECT * FROM tabla WHERE Username=?";
    $results = $this->query($statement, "s", $username);

    if (count($results) === 0) {
      return null;
    }

    return new User($results[0]);
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

$db = new AdatokDatabaseManager(
  host: $config->getDatabaseHost(),
  username: $config->getDatabaseUsername(),
  password: $config->getDatabasePassword(),
  databaseName: $config->getDatabaseName()
);

$user = $db->findUserByUsername($request->username);
echo "'{$user->username}' kedvenc színe: {$user->favoriteColor}";
?>