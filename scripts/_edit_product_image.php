<?php

require_once "../lib/require.php";
require_once "../lib/User.php";
require_once "../lib/Seller.php";

if (!isset($_POST['submit']))
  Utils\redirect('../index.php');

$product_id = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_SPECIAL_CHARS);
$image = $_FILES['image'];

$type = User::getCurrentUserType();

if ($type === 'member')
  $user = Member::getCurrentUser();
else if ($type === 'seller')
  $user = Seller::getCurrentUser();
else if ($type === 'admin')
  $user = Admin::getCurrentUser();
$product = Product::getProducts($product_id)[0];
$seller = Product::getSellerById($product_id);

if (!$user)
  Utils\redirect('../index.php');
if (!$image)
  Utils\redirect('../index.php');
if (!$product_id)
  Utils\redirect('../index.php');
if ($type !== 'seller')
  Utils\redirect('../index.php');
if (
  User::getUserIdAttribute($user)
  !== User::getUserIdAttribute($seller)
)
  Utils\redirect('../index.php');

if (Seller::updateProductImage(
  $product_id,
  $image,
))
  Utils\redirectPage("Successfully edited product image");
else
  Utils\redirectPage("ERROR: Unable to edit product image");