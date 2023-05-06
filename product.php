<?php if (!$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_ENCODED)): ?>
  <script type="module">
    import { redirect } from './js/utils.js';
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

  $user_is_a_member = isset($user) && (
    User::getCurrentUserType() === 'member'
  );
?>

<h1><?= Product::getProductNameAttribute($product); ?></h1>
<img src="<?= Product::getImagePath($product) ?>">
<p>Seller: <?= Seller::getUserNameAttribute($seller) ?></p>

<?php if($user_is_seller): ?>
  <a href="product_edit.php?id=<?= $id ?>">Edit Product</a>
  <form action="scripts/_delete_product.php" method="POST">
    <input 
      type="hidden" 
      name="id" 
      value="<?= $id ?>"
    >
    <input type="submit" name="submit" value="Delete Product">
  </form>
<?php endif; ?>

<?php if($user_is_a_member): ?>
  <dialog id="reportDialog">
    <form 
      action="scripts/_report_seller.php"
      method="POST"
    >
      <input 
        type="hidden" 
        name="member_id" 
        value="<?= User::getUserIdAttribute($user) ?>"
      >
      <input 
        type="hidden" 
        name="seller_id" 
        value="<?= Seller::getUserIdAttribute($seller) ?>"
      >

      <button value="cancel" formmethod="dialog">Cancel</button>

      <label for="message">Report Message</label>
      <textarea 
        name="message" 
        id="message" 
        cols="30" 
        rows="10"
      ></textarea>

      <input type="submit" name="submit" value="Submit">
    </form>
  </dialog>
  <div class="dialog-button-container">
    <button id="showDialog">Report Seller</button>
  </div>
<?php endif; ?>

<script src="js/fetch.js"></script>
<script src="js/product.js"></script>