<?php

require_once "../lib/require.php";
require_once "../lib/User.php";
require_once "../lib/Member.php";
require_once "../lib/Seller.php";

if (!$id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_SPECIAL_CHARS))
  Utils\redirect('../index.php');

$user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_SPECIAL_CHARS);
$image = $_FILES['image'];

$type = User::getCurrentUserType();

if ($type === 'member')
  $user = Member::getCurrentUser();
else if ($type === 'seller')
  $user = Seller::getCurrentUser();
else if ($type === 'admin')
  $user = Admin::getCurrentUser();

if (!$user)
  Utils\redirect('../index.php');
if (!$image)
  Utils\redirect('../index.php');

if (($type === 'member' && Member::updateUserImage(
  $user_id,
  $image,
)) || ($type === 'seller' && Seller::updateUserImage(
  $user_id,
  $image,
)))
  Utils\redirectPage("Successfully edited image");
else
  Utils\redirectPage("ERROR: Unable to edit account image");