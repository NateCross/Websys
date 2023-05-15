<?php

require_once "../lib/require.php";
require_once "../lib/User.php";
require_once "../lib/Seller.php";

?>

<?php if (!isset($_POST['submit'])): ?>

<p>Invalid form. Please try again.</p>
<script type="module">
  import { redirect } from '../js/utils.js';
  redirect('/', 3000);
</script>

<?php die(); endif; ?>

<?php
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
?>

<!-- Check for errors -->
<?php if (!$user): ?>
  <p>No user. Please try again.</p>
  <script type="module">
    import { redirect } from '../js/utils.js';
    redirect('/', 3000);
  </script>
  <?php die(); ?>
<?php elseif (!$image): ?>
  <script type="module">
    import { redirect } from '../js/utils.js';
    redirect();
  </script>
  <?php die(); ?>
<?php elseif (!$product_id): ?>
  <script type="module">
    import { redirect } from '../js/utils.js';
    redirect();
  </script>
  <?php die(); ?>
<?php elseif (!$type === 'seller'): ?>
  <p>Not a seller. Please try again.</p>
  <script type="module">
    import { redirect } from '../js/utils.js';
    redirect('/', 3000);
  </script>
  <?php die(); ?>
<?php elseif (
  User::getUserIdAttribute($user) 
  !== User::getUserIdAttribute($seller)
): ?>
  <p>Not the seller of this product. Please try again.</p>
  <script type="module">
    import { redirect } from '../js/utils.js';
    redirect('/', 3000);
  </script>
  <?php die(); ?>
<?php endif; ?>

<?php if (Seller::updateProductImage(
  $product_id,
  $image,
))
  Utils\redirectPage("Successfully edited product image");
else
  Utils\redirectPage("ERROR: Unable to edit product image");