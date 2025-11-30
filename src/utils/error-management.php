<?php

enum ErrorCode: int {
  case UNEXPECTED_ERROR = 5030;
  case BAD_USERNAME_ERROR = 4011;
  case BAD_PASSWORD_ERROR = 4012;
}

function fail(ErrorCode $errorCode, string $debugMessage) {
    error_log($debugMessage);
    Router::redirect("/?error={$errorCode->value}");
}

function debug($object) {
  $printableObject = print_r($object, true);
  echo("<pre>{$printableObject}</pre><br />");
}
