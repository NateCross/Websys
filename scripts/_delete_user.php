<?php

require_once "../lib/require.php";

if (!$id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_SPECIAL_CHARS))
  Utils\redirect('../index.php');

require_once "../lib/Seller.php";
require_once "../lib/Admin.php";
require_once "../lib/Member.php";

$type = User::getCurrentUserType();
if ($type === 'member') {
  if (!Member::deleteUser($id))
    Utils\redirectPage('ERROR: Failed to delete user');
} else if ($type === 'seller') {
  if (!Seller::deleteUser($id))
    Utils\redirectPage('ERROR: Failed to delete user');
} else if ($type === 'admin') {
  if (!Admin::deleteUser($id))
    Utils\redirectPage('ERROR: Failed to delete user');
} else {
  Utils\redirect('../index.php');
}

Utils\redirectPage('Successfully deleted user');