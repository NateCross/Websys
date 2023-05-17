<?php

require_once "../lib/require.php";
require_once "../lib/User.php";
require_once "../lib/Seller.php";
require_once "../lib/Product.php";

?>

<?php 

if (!isset($_POST['submit']))
  Utils\redirect('../index.php');

[
  'name' => $name,
  'description' => $description,
  'quantity' => $quantity,
  'price' => $price,
  'category' => $category,
  'product_id' => $product_id,
] = filter_input_array(INPUT_POST, [
  'name' => FILTER_SANITIZE_SPECIAL_CHARS,
  'description' => FILTER_SANITIZE_SPECIAL_CHARS,
  'quantity' => FILTER_VALIDATE_INT,
  'price' => FILTER_VALIDATE_FLOAT,
  'category' => FILTER_SANITIZE_SPECIAL_CHARS,
  'product_id' => FILTER_VALIDATE_INT,
]);

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
if (!$product_id)
  Utils\redirect('../index.php');
if ($type !== 'admin') {
  if ($type !== 'seller')
    Utils\redirect('../index.php');
  if (
    User::getUserIdAttribute($user)
    !== User::getUserIdAttribute($seller)
  )
    Utils\redirect('../index.php');
}

if (Seller::updateProduct($product_id, $name, $description, $quantity, $price, $category))
  Utils\redirectPage("Successfully edited product");
else
    Utils\redirectPage("ERROR: Unable to edit product");