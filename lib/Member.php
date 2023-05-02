<?php

require_once 'User.php';

/**
 * Model for the member user type in the system
 * 
 * To store the member, sessions are used instead 
 * of static variables
 */
class Member extends User {
  protected static function getTableName(): string {
    return 'member';
  }
}