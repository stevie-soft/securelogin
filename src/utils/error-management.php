<?php

enum ErrorCode: int {
  case BAD_USERNAME_ERROR = 4011;
  case BAD_PASSWORD_ERROR = 4012;
  case UNEXPECTED_ERROR = 5030;
  case READING_PASSWORDS_FILE_ERROR = 5031;
  case CONNECTING_TO_DATABASE_ERROR = 5032;
  case READING_DOTENV_ERROR = 5033;

}

function fail(ErrorCode $errorCode) {
    $path = "/broken?error={$errorCode->value}";
    Router::redirect($path);
}

function debug($object) {
  $printableObject = print_r($object, true);
  echo("<pre>{$printableObject}</pre><br />");
}
