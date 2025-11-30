<?php

class User {
  public int $id;
  public string $username;
  public string $favoriteColor;

  public function __construct(array $values) {
    $this->id = $values["Sor"];
    $this->username = $values["Username"];
    $this->favoriteColor = $values["Titkos"];
  }

  public static function findByUsername(string $username): User | null {
    global $db;
    
    $statement = "SELECT * FROM tabla WHERE Username=?";
    $userValues = $db->query($statement, "s", $username);

    if (!$userValues) {
      return null;
    }

    return new User($userValues);
  }
}
