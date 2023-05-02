<?php

if (!isset($_POST['submit'])) return;

require_once "../lib/require.php";
require_once "../lib/Member.php";
require_once "../lib/Seller.php";

$email = filter_input(
  INPUT_POST, 
  'email', 
  FILTER_VALIDATE_EMAIL,
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
$type = filter_input(
  INPUT_POST,
  'type',
  FILTER_SANITIZE_SPECIAL_CHARS,
);

if ($type === "Member") {
  if (Member::register($email, $username, $password))
    header('Location: /');
} else if ($type === "Seller") {
  if (Seller::register($email, $username, $password))
    header('Location: /');
}

echo "Unable to create account. Please try again.";