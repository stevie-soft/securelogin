<?php

class EnvVarManager {

  private array $env;

  public function __construct(array $source) {
    $this->env = $source;
  }

  public function set(string $key, string $value) {
    $this->env[$key] = $value;
  }

  public function get(string $key): string {
    return $this->env[$key];
  }

  public function loadDotEnv(string $filePath = ".env") {
    $lines = file($filePath, FILE_IGNORE_NEW_LINES);
    
    if (!$lines) {
      error_log("Could not load env file at '{$filePath}'. ");
      fail(ErrorCode::READING_DOTENV_ERROR);
    }

    foreach ($lines as $line) {
      [$key, $value] = explode('=', $line, 2);
      $key = trim($key);
      $value = trim($value);

      $this->set($key, $value);
    }
  }
}