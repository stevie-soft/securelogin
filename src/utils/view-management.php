<?php

class View {
  public static function render(string $viewName, array $variables = []) {
    echo self::renderHtml($viewName, $variables);
    exit();
  }

  private static function renderHtml(string $viewName, array $variables = []) {
    $filePath = __DIR__ . "/../views/{$viewName}.html";
    $html = file_get_contents($filePath, true);

    foreach ($variables as $key => $value) {
      $placeholder = "%{$key}%";
      $html = str_replace($placeholder, $value, $html);
    }

    return $html;
  }
}