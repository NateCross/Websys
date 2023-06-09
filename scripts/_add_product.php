<?php

require_once "../lib/require.php";
require_once "../lib/User.php";
require_once "../lib/Seller.php";

if (!isset($_FILES['image']) || !isset($_POST['submit'])) 
  Utils\redirect('../index.php');

[
  'name' => $name,
  'description' => $description,
  'quantity' => $quantity,
  'price' => $price,
  'category' => $category,
] = filter_input_array(INPUT_POST, [
  'name' => FILTER_SANITIZE_SPECIAL_CHARS,
  'description' => FILTER_SANITIZE_SPECIAL_CHARS,
  'quantity' => FILTER_VALIDATE_INT,
  'price' => FILTER_VALIDATE_FLOAT,
  'category' => FILTER_SANITIZE_SPECIAL_CHARS,
]);
$image = $_FILES['image'];

if (!$name || !$description || !$quantity || !$price || !$category)
  Utils\redirect('../product_add.php');
if (Product::getProductByName($name))
  Utils\redirectPage('ERROR: A product with this name already exists', 'product_add.php');

?>

<?php if (Seller::addProduct(
  $name, 
  $description, 
  $quantity, 
  $price, 
  $category,
  $image, 
))
  Utils\redirectPage("Successfully added product");
else
  Utils\redirectPage("ERROR: Unable to add product");