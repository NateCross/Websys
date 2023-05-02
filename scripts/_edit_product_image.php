<?php

require_once "../lib/require.php";
require_once "../lib/User.php";
require_once "../lib/Seller.php";

?>

<?php if (!isset($_POST['submit'])): ?>

<p>Invalid form. Please try again.</p>
<script type="module">
  import { redirect } from '../lib/utils.js';
  redirect('/', 3000);
</script>

<?php die(); endif; ?>

<?php
  $product_id = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_SPECIAL_CHARS);
  $image = $_FILES['image'];

  $user = User::getCurrentUser();
  $type = User::getCurrentUserType();
  $product = Product::getProducts($product_id)[0];
  $seller = Product::getSellerById($product_id);
?>

<!-- Check for errors -->
<?php if (!$user): ?>
  <p>No user. Please try again.</p>
  <script type="module">
    import { redirect } from '../lib/utils.js';
    redirect('/', 3000);
  </script>
  <?php die(); ?>
<?php elseif (!$image): ?>
  <script type="module">
    import { redirect } from '../lib/utils.js';
    redirect();
  </script>
  <?php die(); ?>
<?php elseif (!$product_id): ?>
  <script type="module">
    import { redirect } from '../lib/utils.js';
    redirect();
  </script>
  <?php die(); ?>
<?php elseif (!$type === 'seller'): ?>
  <p>Not a seller. Please try again.</p>
  <script type="module">
    import { redirect } from '../lib/utils.js';
    redirect('/', 3000);
  </script>
  <?php die(); ?>
<?php elseif (
  User::getUserIdAttribute($user) 
  !== User::getUserIdAttribute($seller)
): ?>
  <p>Not the seller of this product. Please try again.</p>
  <script type="module">
    import { redirect } from '../lib/utils.js';
    redirect('/', 3000);
  </script>
  <?php die(); ?>
<?php endif; ?>

<?php if (Seller::updateProductImage(
  $product_id,
  $image,
)): ?>
  <p>Successfully edited product.</p>
  <a href="/">Click to return</a>
<?php else: ?>
  <p>An error has occurred.</p>
  <a href="/">Click to return</a>
<?php endif; ?>