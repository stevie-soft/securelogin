<?php

class Config {
  private EnvVarManager $envVarManager;

  public function __construct(array $source) {
    $this->envVarManager = new EnvVarManager($source);
  }

  public function loadDotEnv(string $filePath = ".env") {
    $this->envVarManager->loadDotEnv($filePath);
  }

  public function getPasswordsFilePath(): string {
    return $this->envVarManager->get("PASSWORDS_FILEPATH");
  }

  public function getDecryptionKey(): array {
    $keyAsString = $this->envVarManager->get("DECRYPTION_KEY");

    return array_map(
      callback: 'intval', 
      array: explode(',', $keyAsString)
    );
  }

  public function getDatabaseHost(): string {
    return $this->envVarManager->get("DB_HOST");
  }

  public function getDatabaseUsername(): string {
    return $this->envVarManager->get("DB_USERNAME");
  }

  public function getDatabasePassword(): string {
    return $this->envVarManager->get("DB_PASSWORD");
  }

  public function getDatabaseName(): string {
    return $this->envVarManager->get("DB_NAME");
  }
}

