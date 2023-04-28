<?php

if (!isset($_POST['submit'])) return;

require_once "../../require/require.php";
require_once "../../models/Member.php";

$email = filter_input(
  INPUT_POST, 
  'email', 
  FILTER_VALIDATE_EMAIL | FILTER_SANITIZE_EMAIL,
);
$username = filter_input(
  INPUT_POST, 
  'username', 
  FILTER_SANITIZE_SPECIAL_CHARS,
);
$password = filter_input(
  INPUT_POST, 
  'password', 
  FILTER_SANITIZE_SPECIAL_CHARS,
);

if (Member::register($email, $username, $password))
  header('Location: /');

echo "Unable to create account. Please try again.";