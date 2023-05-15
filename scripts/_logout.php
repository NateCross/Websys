<?php

require_once "../lib/require.php";
require_once "../lib/User.php";

$logout = User::logout();

if ($logout) {
  Utils\redirectPage("Successfully logged out");
} else {
  Utils\redirectPage("ERROR: Unable to log out");
}