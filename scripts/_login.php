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
  if (Member::login($email, $password))
    header('Location: /');
} else if ($type === "Seller") {
  if (Seller::login($email, $password))
    header('Location: /');
}

echo "Unable to login. Please try again.";