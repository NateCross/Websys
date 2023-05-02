<?php if (!$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_ENCODED)) : ?>
  <p>No product ID to delete. Redirecting to home page...</p>
  <script type="module">
    import {
      redirect
    } from 'lib/utils.js';
    redirect('/', 3000);
  </script>
  <?php die(); ?>
<?php endif; ?>

<?php

require_once "../lib/require.php";
require_once "../lib/Seller.php";
require_once "../lib/Product.php";

$product = Product::getProducts($id);
$user = Seller::getCurrentUser();

$user_id = Seller::getUserIdAttribute($user);
$seller_id = Product::getProductSellerIdAttribute($product);

?>

<?php if (!$product): ?>
  <p>Product does not exist. Redirecting to home page...</p>
  <script type="module">
    import {
      redirect
    } from 'lib/utils.js';
    redirect('/', 3000);
  </script>
  <?php die(); ?>
<?php endif; ?>

<?php if (!$user): ?>
  <p>User does not exist. Redirecting to home page...</p>
  <script type="module">
    import {
      redirect
    } from 'lib/utils.js';
    redirect('/', 3000);
  </script>
  <?php die(); ?>
<?php endif; ?>

<!-- Error checks before delete -->
<?php if (Seller::getCurrentUserType() !== 'seller'): ?>
  <p>User is not a seller. Redirecting to home page...</p>
  <script type="module">
    import {
      redirect
    } from 'lib/utils.js';
    redirect('/', 3000);
  </script>
  <?php die(); ?>
<?php endif; ?>

<?php if ($user_id !== $seller_id): ?>
  <p>User is not the seller of this item. Redirecting to home page...</p>
  <script type="module">
    import {
      redirect
    } from 'lib/utils.js';
    redirect('/', 3000);
  </script>
  <?php die(); ?>
<?php endif; ?>

<?php if (Seller::getCurrentUserType() !== 'seller'): ?>
  <p>User is not a seller. Redirecting to home page...</p>
  <script type="module">
    import {
      redirect
    } from 'lib/utils.js';
    redirect('/', 3000);
  </script>
  <?php die(); ?>
<?php endif; ?>

<?php if (!Product::deleteProduct($id)): ?>

<?php endif; ?>