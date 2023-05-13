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
require_once 'lib/Review.php';

$type = User::getCurrentUserType();

if ($type === 'member')
  $user = Member::getCurrentUser();
else if ($type === 'seller')
  $user = Seller::getCurrentUser();
else if ($type === 'admin')
  $user = Admin::getCurrentUser();
$product = Product::getProducts($id)[0];
$seller = Product::getSellerByProduct($product);

Component\Header(Product::getProductNameAttribute($product));

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

<!-- Display product details -->
<!-- TODO: Change these elements and make them presentable -->
<img src="<?= Product::getImagePath($product) ?>">
<p>Seller: <?= Seller::getUserNameAttribute($seller) ?></p>
<p>Rating: <?= Review::getAverageRating(Product::getProductIdAttribute($product)) ?> / 5</p>
<p>Quantity: <?= Product::getProductQuantityAttribute($product) ?></p>

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

<?php if($user_is_a_member && Product::getProductQuantityAttribute($product)): ?>
  <div class="add-to-cart-container">
    <form 
      action="scripts/_add_to_cart.php"
      method="POST"
    >
      <input type="hidden" name="product_id" value="<?= $id ?>">
      <input 
        type="number" 
        name="quantity_purchased" 
        id="quantity_purchased"
        value="1"
        min="1"
        max="<?= Product::getProductQuantityAttribute($product) ?>"
        required
      >
      <input type="submit" name="submit" value="Add to Cart">

    </form>
    <output class="add-to-cart-total">
      <!-- This is unused in the form, it's just -->
      <!-- for manipulating subtotal display -->
      <input type="hidden" id="product_price" name="product_price" value="<?= Product::getProductPriceAttribute($product) ?>">

      Total: <span id="subtotal"> - </span>
    </output>
  </div>

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

<?php Component\Footer(); ?>