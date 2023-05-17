<?php 

if (!$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_SPECIAL_CHARS))
  Utils\redirect('../index.php');

require_once "../lib/require.php";
require_once "../lib/Seller.php";
require_once "../lib/Admin.php";
require_once "../lib/Product.php";

$product = Product::getProducts($id)[0];
$user = Seller::getCurrentUser();

$user_id = Seller::getUserIdAttribute($user);
$seller_id = Product::getProductSellerIdAttribute($product);

if (!$product)
  Utils\redirect('../index.php');
if (!$user)
  Utils\redirect('../index.php');
if (Admin::getCurrentUserType() !== 'admin') {
  if (Seller::getCurrentUserType() !== 'seller')
    Utils\redirect('../index.php');
  if ($user_id !== $seller_id)
    Utils\redirect('../index.php');
}

if (!Product::deleteProduct($id))
  Utils\redirectPage('ERROR: Failed to delete item');
else
  Utils\redirectPage('Successfully deleted item');