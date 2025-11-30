<?php

class View {
  public static function render(string $viewName, array $variables = []) {
    echo self::renderHtml($viewName, $variables);
    exit();
  }

  private static function renderHtml(string $viewName, array $variables = []) {
    $html = file_get_contents("../views/{$viewName}.html");

    foreach ($variables as $key => $value) {
      $placeholder = "%{$key}%";
      $html = str_replace($placeholder, $value, $html);
    }

    return $html;
  }
}