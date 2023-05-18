<?php

require_once '../lib/require.php';
require_once '../lib/User.php';
require_once '../lib/Member.php';
require_once '../lib/Product.php';
require_once '../lib/Review.php';

if (!isset($_POST['submit']))
  Utils\redirectPage("ERROR: Invalid form");

$type = User::getCurrentUserType();
if (!$type || $type !== 'member')
  Utils\redirectPage("ERROR: Not a member");

[
  'member_id' => $member_id,
  'product_id' => $product_id,
  'rating' => $rating,
  'comment' => $comment,
] = filter_input_array(INPUT_POST, [
  'member_id' => FILTER_VALIDATE_INT,
  'product_id' => FILTER_VALIDATE_INT,
  'rating' => FILTER_VALIDATE_INT,
  'comment' => FILTER_SANITIZE_SPECIAL_CHARS,
]);

if (!$member = Member::getUserViaId($member_id))
  Utils\redirectPage("ERROR: Member does not exist");

if (!$product = Product::getProducts($product_id))
  Utils\redirectPage("ERROR: Product does not exist");

if (!Review::addReview(
  $member_id,
  $product_id,
  $rating,
  $comment,
)) Utils\redirectPage("ERROR: Failed to add review");

Utils\redirectPage("Successfully reviewed product", "product.php?id=" . $product_id);