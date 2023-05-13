<?php

require_once '../lib/require.php';
require_once '../lib/User.php';
require_once '../lib/Member.php';
require_once '../lib/Product.php';
require_once '../lib/Review.php';

if (!isset($_POST['submit']))
  ErrorHandler::handleError('Invalid POST request');
  // Utils\redirect('../index.php');

$type = User::getCurrentUserType();
if (!$type || $type !== 'member')
  ErrorHandler::handleError('Not a member');

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
  ErrorHandler::handleError('No member');

if (!$product = Product::getProducts($product_id))
  ErrorHandler::handleError('No product');

if (!Review::addReview(
  $member_id,
  $product_id,
  $rating,
  $comment,
)) ErrorHandler::handleError('Failed to add review');

Utils\redirect("../product.php?id=" . $product_id);

