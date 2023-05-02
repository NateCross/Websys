<?php if (!$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_ENCODED)): ?>
  <script type="module">
    import { redirect } from 'lib/utils.js';
    redirect();
  </script>
  <?php die(); ?>
<?php endif; ?>

<?php

require_once 'lib/require.php';
require_once 'lib/Product.php';
require_once 'lib/User.php';
require_once 'lib/Seller.php';

$user = User::getCurrentUser();
$product = Product::getProducts($id)[0];
$seller = Product::getSellerByProduct($product);

?>

<?php if(!$product): ?>
  <p>Product not found. Redirecting to home page...</p>
  <script type="module">
    import { redirect } from 'lib/utils.js';
    redirect('/', 3000);
  </script>
<?php die(); endif; ?>

<?php
  $user_is_seller = isset($user) && (
    User::getCurrentUserType() === 'seller'
    && User::getUserIdAttribute($user)
      === Seller::getUserIdAttribute($seller)
  );
?>

<h1><?= Product::getProductNameAttribute($product); ?></h1>
<img src="<?= Product::getImagePath($product) ?>">
<p>Seller: <?= Seller::getUserNameAttribute($seller) ?></p>

<?php if($user_is_seller): ?>
  <a href="product_edit.php?id=<?= $id ?>">Edit Product</a>
<?php endif; ?>