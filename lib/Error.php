<?php

class ErrorHandler {
  public static function handleError(string $message, int $duration = 3000) {
    Session::set('error', $message);
    Session::set('error_duration', $duration);
    require_once "../error.php";
  }
}