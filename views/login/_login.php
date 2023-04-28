<?php

if (!isset($_POST['submit'])) return;

require_once "../../require/require.php";
require_once "../../models/Member.php";

$email = filter_input(
  INPUT_POST, 
  'email', 
  FILTER_VALIDATE_EMAIL | FILTER_SANITIZE_EMAIL,
);
$password = filter_input(
  INPUT_POST, 
  'password', 
  FILTER_SANITIZE_SPECIAL_CHARS,
);

if (Member::login($email, $password))
  header("Location: /");

echo "Unable to login. Please try again.";