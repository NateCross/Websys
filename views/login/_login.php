<?php

if (!isset($_POST['submit'])) return;

require_once "../../require/require.php";
require_once "../../models/Member.php";

// Filter input

$username = filter_input(INPUT_POST, 'username');
$password = filter_input(INPUT_POST, 'password');

[$memberId, $memberUsername, $memberPassword] = 
  Member::getMemberUsername($username);

$doPasswordsMatch = password_verify($password, $memberPassword);