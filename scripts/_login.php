<?php

if (!isset($_POST['submit'])) return;

require_once "../lib/require.php";
require_once "../lib/Member.php";
require_once "../lib/Seller.php";
require_once "../lib/Admin.php";

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);
$type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_SPECIAL_CHARS);

if ($type === "Member") {
  if (Member::login($email, $password))
    Utils\redirectPage("Logged in as $email");
} else if ($type === "Seller") {
  if (Seller::login($email, $password))
    Utils\redirectPage("Logged in as $email");
} else if ($type === "Admin") {
  if (Admin::login($email, $password))
    Utils\redirectPage("Logged in as $email", 'admin-panel.php');
}

Utils\redirectPage("ERROR: Unable to login", 'login.php');