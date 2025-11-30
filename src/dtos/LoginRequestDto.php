<?php

class LoginRequestDto {
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
