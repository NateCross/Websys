<?php

require_once 'User.php';

class Admin extends User {
  protected static function getTableName(): string {
    return 'admin';
  }
}