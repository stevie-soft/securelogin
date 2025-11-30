<?php

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
      fail(
        ErrorCode::UNEXPECTED_ERROR, 
        "Could not load passwords file at '{$this->passwordsFilePath}'"
      );
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
