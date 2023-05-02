<?php

require_once '../require/require.php';
require_once '../models/User.php';
require_once '../models/Product.php';

$user = User::getCurrentUser();
$type = User::getCurrentUserType();

if ($user) {
  echo "Hello, " . $user['name'] . "<br>";
  echo "Currently logged in as a: " . $type . "<br>";
}

?>

<a href="/login">Login</a>

<a href="/register">Register</a>

<?php if (User::getCurrentUser()): ?>

<button id="logout">Logout</button>

<?php endif; ?>

<?php if ($type === 'seller'): ?>

<a href="/product/add">Add Product For Sale</a>

<?php endif; ?>

<?php
  $products = Product::getProducts();
  var_dump($products);
?>

<script src='index.js'></script>