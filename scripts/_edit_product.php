<?php

require_once "../lib/require.php";
require_once "../lib/User.php";
require_once "../lib/Seller.php";
require_once "../lib/Product.php";

?>

<?php if (!isset($_POST['submit'])): ?>

<p>Invalid form. Please try again.</p>
<script type="module">
  import { redirect } from '../js/utils.js';
  redirect('/', 3000);
</script>

<?php die(); endif; ?>

<?php
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
?>

<!-- Check for errors -->
<?php if (!$user): ?>
  <p>No user. Please try again.</p>
  <script type="module">
    import { redirect } from '../js/utils.js';
    redirect('/', 3000);
  </script>
  <?php die(); ?>
<?php elseif (!$product_id): ?>
  <script type="module">
    import { redirect } from '../js/utils.js';
    redirect();
  </script>
  <?php die(); ?>
<?php elseif($type !== 'admin'): ?>
  <?php if (!$type === 'seller'): ?>
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
<?php endif; ?>

<?php 

if (Seller::updateProduct($product_id, $name, $description, $quantity, $price, $category))
  Utils\redirectPage("Successfully edited product");
else
    Utils\redirectPage("ERROR: Unable to edit product");