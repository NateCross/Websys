<?php

class ErrorHandler {
  public static function handleError(string $message) {
    Session::set('error', $message);
    require_once "../error.php";
  }
}