<?php

require_once '../require/require.php';

if ($user = Session::get('user')) {
  echo "Hello, " . $user['name'] . "<br>";
  echo "Currently logged in as a: " . Session::get('type') . "<br>";
}

?>

<a href="/login">Login</a>

<a href="/register">Register</a>