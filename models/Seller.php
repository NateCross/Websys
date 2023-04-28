<?php

require_once 'User.php';

class Seller extends User {
  protected static function getTableName(): string {
    return 'seller';
  }
}