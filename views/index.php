<?php

require_once '../require/require.php';

if ($member = Session::get('member')) {
  echo "Hello, " . $member['name'] . "<br>";
}

?>

<a href="/login">Login</a>

<a href="/register">Register</a>