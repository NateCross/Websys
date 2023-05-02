<?php

require_once 'lib/require.php';
require_once 'lib/User.php';
require_once 'lib/Product.php';

$user = User::getCurrentUser();
$type = User::getCurrentUserType();

if ($user) {
  echo "Hello, " . $user['name'] . "<br>";
  echo "Currently logged in as a: " . $type . "<br>";
}

?>

<a href="login.php">Login</a>

<a href="register.php">Register</a>

<?php if (User::getCurrentUser()): ?>

<button id="logout">Logout</button>

<?php endif; ?>

<?php if ($type === 'seller'): ?>

<a href="product_add.php">Add Product For Sale</a>

<?php endif; ?>

<?php if ($products = Product::getProducts()) { ?>

<ul class="product-list-container">
  <?php foreach ($products as $product): ?>

  <li>
    <a 
      href="product.php?id=<?= Product::getProductIdAttribute($product) ?>"
    >
      <!-- TODO: Adjust size through CSS  -->
      <img 
        src="<?= Product::getImagePath($product); ?>" 
      >
      <?= Product::getProductNameAttribute($product); ?>
      </a>
  </li>

  <?php endforeach; ?>
</ul>

<?php } ?>

<script src='index.js'></script>