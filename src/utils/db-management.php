<?php

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
      error_log("Could not connect to MySQL database '{$this->databaseName}' at '{$this->host}' with user '{$this->username}'. ");
      fail(ErrorCode::CONNECTING_TO_DATABASE_ERROR);
    }
  }

  public function query(string $statementTemplate, string $paramTypes, string ...$params): array {
    if (!$this->connection) {
      $this->connect();
    }

    $statement = $this->connection->prepare($statementTemplate);
    $statement->bind_param($paramTypes, ...$params);
    $statement->execute();

    $result = $statement->get_result();

    return $result->fetch_assoc();
  }
}
