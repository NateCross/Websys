<?php

if (!isset($_POST['submit'])) return;

require_once "../lib/require.php";
require_once "../lib/Member.php";
require_once "../lib/Seller.php";
require_once "../lib/Admin.php";

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$username = filter_input(INPUT_POST, 'username', FILTER_DEFAULT);
$password = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);
$confirm_password = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_SPECIAL_CHARS);
$address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_SPECIAL_CHARS);
$contact_number = filter_input(INPUT_POST, 'contact_number', FILTER_DEFAULT);
$type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_SPECIAL_CHARS);

if ($password !== $confirm_password)
  Utils\redirectPage("Passwords do not match", 'login.php');
if ($type === "Member") {
  if (Member::register($email, $username, $password, $address, $contact_number))
    Utils\redirectPage("Registered as $email", 'login.php');
} else if ($type === "Seller") {
  if (Seller::register($email, $username, $password, $address, $contact_number))
    Utils\redirectPage("Registered as $email", 'login.php');
} else if ($type === "Admin") {
  if (Admin::register($email, $username, $password, $address, $contact_number))
    Utils\redirectPage("Registered as $email", 'admin-panel.php');
}

Utils\redirectPage("ERROR: Unable to register", "register.php");