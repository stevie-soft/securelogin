<?php

$config = new Config($_ENV);
$config->loadDotEnv(__DIR__ . "/../.env");

$db = new DatabaseManager(
  host: $config->getDatabaseHost(),
  username: $config->getDatabaseUsername(),
  password: $config->getDatabasePassword(),
  databaseName: $config->getDatabaseName()
);