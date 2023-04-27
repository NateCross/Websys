<?php

if (!isset($_POST['submit'])) return;

require_once dirname(__DIR__, 2) . "/require/require.php";
require_once "../../models/Member.php";

var_dump($_POST);

// Filter input

$username = filter_input(INPUT_POST, 'username');
$password = filter_input(INPUT_POST, 'password');

Member::createMember($username, $password);


// Hash password for better security
// $saltedPass = password_hash($password, PASSWORD_BCRYPT);

// var_dump($username);