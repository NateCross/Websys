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
<?php if ($average_rating = Review::getAverageRating(Product::getProductIdAttribute($product))): ?>
  <p>Rating: <?= $average_rating ?> / 5</p>
<?php endif; ?>
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

<?php if ($reviews = Review::getReviews(Product::getProductIdAttribute($product))): ?>
  <div class="reviews-container">
    <?php foreach ($reviews as $review): ?>
      <div class="review-post-container">
        <p class="review-author">
          <?= Review::getMemberName($review) ?>
        </p>
        <p class="review-timestamp">
          <?= Review::getTimestamp($review) ?>
        </p>
        <p class="review-rating">
          <?= Review::getRating($review) ?> / 5
        </p>
        <p class="review-comment">
          <?= Review::getComment($review) ?>
        </p>
        <?php if ($type === 'member' 
          && Member::getUserIdAttribute($user) 
            === Review::getMemberId($review)
        ): ?>
          <div class="review-actions-container">
            <button
              class="review-actions-edit"
              value="<?= Review::getId($review) ?>"
            >
              Edit
            </button>
            <form 
              action="scripts/_delete_review.php"
              method="POST"
            >
              <input 
                type="hidden" 
                name="review_id"
                value="<?= Review::getId($review) ?>"
              >
              <button name="submit" value="submit" type="submit">Delete</button>
            </form>
          </div>
        <?php endif; ?>
      </div>
    <?php endforeach ?>
    <dialog
      id="edit_review_dialog"
    >
      <form 
        action="scripts/_edit_review.php"
        method="POST"
      >
        <!-- Change the value of review_id in the JS -->
        <input 
          type="hidden" 
          name="review_id" 
          id="review_id"
        >
        <div class="review-rating-container">
          <label for="rating">Rating</label>
          <input 
            type="number" 
            name="rating" 
            id="rating"
            value="5"
            min="1"
            max="5"
          >
        </div>
        <div class="review-comment-container">
          <label for="comment">Comment</label>
          <textarea name="comment" id="comment" cols="30" rows="10"></textarea>
        </div>
        <div class="review-buttons-container">
          <button type="submit" name="submit" value="submit">Submit</button>
          <button value="cancel" id="cancel" formmethod="dialog">Cancel</button>
        </div>
      </form>
    </dialog>
  </div>
<?php endif; ?>

<script src="js/fetch.js"></script>
<script src="js/product.js"></script>

<?php Component\Footer(); ?>