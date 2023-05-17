<?php 

if (!$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_ENCODED))
  Utils\redirect('index.php');

require_once 'lib/require.php';
require_once 'lib/Product.php';
require_once 'lib/User.php';
require_once 'lib/Member.php';
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
if (!$product) Utils\redirect('index.php');

$seller = Product::getSellerByProduct($product);

Component\Header(Product::getProductNameAttribute($product));

if (!$product) 
  Utils\redirectPage("Product not found");

$user_is_seller = isset($user) && (
  User::getCurrentUserType() === 'seller'
  && User::getUserIdAttribute($user)
    === Seller::getUserIdAttribute($seller)
);

$user_is_a_member = isset($user) && (
  User::getCurrentUserType() === 'member'
);

$user_is_admin = isset($user) && (
  User::getCurrentUserType() === 'admin'
);

?>

<div class="product-display-main-container">
  <div class="product-display-left-container">
    <img src="<?= Product::getImagePath($product) ?>">
  </div>
  <div class="product-display-right-container">
    <h1><?= Product::getProductNameAttribute($product); ?></h1>
    <p>
      <a href="seller.php?id=<?= Seller::getUserNameAttribute($seller) ?>">
        Seller: <?= Seller::getUserNameAttribute($seller) ?>
      </a>
    </p>
    <p>
      <a href="category.php?id=<?= Product::getProductCategoryId($product) ?>">
        Category:
        <?= Product::getProductCategoryAttribute($product) ?>
      </a>
    </p>
    <?php if ($average_rating = Review::getAverageRating(Product::getProductIdAttribute($product))): ?>
      <p>Rating: <?= number_format($average_rating, 2) ?>★ / 5.00★</p>
    <?php endif; ?>
    <p>Quantity: <?= Product::getProductQuantityAttribute($product) ?></p>
    <p>Price: <?= Utils\formatCurrency(Product::getProductPriceAttribute($product)) ?></p>
    <?php if ($user_is_a_member): ?>
      <div class="wishlist-button-container">
        <form 
          action="scripts/_wishlist.php" 
          method="post" 
          enctype="multipart/form-data"
        >
          <input
            type="hidden"
            name="prod_id"
            value="<?= $id ?>"
          >
          <input type="submit" name="submit" value="Add to Wishlist">
        </form>
      </div>
    <?php endif; ?>
    <?php if($user_is_seller || $user_is_admin): ?>
      <div class="product-seller-actions-container">

        <a href="product_edit.php?id=<?= $id ?>">
          <button>
            Edit Product
          </button>
        </a>
        <form action="scripts/_delete_product.php" method="POST">
          <input 
            type="hidden" 
            name="id" 
            value="<?= $id ?>"
          >
          <input type="submit" name="submit" value="Delete Product">
        </form>
      </div>
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
          <button
            id="buy_now_button"
          >
            Buy Now
          </button>
        </form>
        <output class="add-to-cart-total">
          <!-- This is unused in the form, it's just -->
          <!-- for manipulating subtotal display -->
          <input type="hidden" id="product_price" name="product_price" value="<?= Product::getProductPriceAttribute($product) ?>">

          <h2>
            Total: <span id="subtotal">-</span>
          </h2>
        </output>
      </div>

      <dialog id="purchase_dialog">
        <h2>Buy Now</h2>
        <form 
          action="scripts/_buy_now.php"
          method="POST"
        >
          <input 
            type="hidden" 
            name="product_id" 
            value="<?= $id ?>"
          >
          <!-- Modified in Javascript -->
          <input 
            type="hidden" 
            name="buy_now_quantity"
            id="buy_now_quantity"
          >

          <div class="bank-details-container">
            <div class="form-input-container">
              <label for="bank">Payment Method</label>
              <div class="bank-input">
                <select name="bank" id="bank">
                  <option value="cod">Cash on Delivery</option>
                  <option value="bdo">BDO</option>
                  <option value="bpi">BPI</option>
                  <option value="gcash">GCash</option>
                  <option value="other">Other</option>
                </select>
                <input type="text" name="bank_other" id="bank_other" hidden>
              </div>
            </div>

            <div class="form-input-container">
              <label for="owner" id="owner_label" hidden>Owner</label>
              <input type="text" name="owner" id="owner" hidden>
            </div>

            <div class="form-input-container">
              <label for="cvv" id="cvv_label" hidden>CVV</label>
              <input type="text" name="cvv" id="cvv" hidden>
            </div>

            <div class="form-input-container">
              <label for="card_number" id="card_number_label" hidden>Card Number</label>
              <input type="text" name="card_number" id="card_number" hidden>
            </div>

            <div class="form-input-container" id="expiration_date_container" hidden>
              <label id="expiration_date_label" hidden>Expiration Date</label>
              <div class="cart-order-expiration-date">
                <select name="expiration_date_month" id="expiration_date_month" hidden>
                  <option value="01">January</option>
                  <option value="02">February </option>
                  <option value="03">March</option>
                  <option value="04">April</option>
                  <option value="05">May</option>
                  <option value="06">June</option>
                  <option value="07">July</option>
                  <option value="08">August</option>
                  <option value="09">September</option>
                  <option value="10">October</option>
                  <option value="11">November</option>
                  <option value="12">December</option>
                </select>
                <input type="number" min="00" max="99" name="expiration_date_year" id="expiration_date_year" hidden>
              </div>
            </div>
          </div>

          <div class="shipping-details-container">
            <div class="form-input-container">
              <label for="address">Address</label>
              <input 
                type="text" 
                name="address" 
                id="address"
                value="<?= Member::getUserAddressAttribute($user) ?>"
                required
              >
            </div>
            <div class="form-input-container">
              <label for="contact_number">Contact Number</label>
              <input 
                type="text" 
                name="contact_number" 
                id="contact_number"
                value="<?= Member::getUserContactNumberAttribute($user) ?>"
                required
              >
            </div>
        </div>
          <input type="submit" name="submit" value="Submit">
          <button value="cancel" formmethod="dialog" id="buy_now_cancel">Cancel</button>
        </form>

      </dialog>

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

          <div class="form-input-container">
            <label for="message">Report Message</label>
            <textarea 
              name="message" 
              id="message" 
              cols="30" 
              rows="10"
            ></textarea>
          </div>

          <input type="submit" name="submit" value="Submit">
          <button value="cancel" formmethod="dialog">Cancel</button>
        </form>
      </dialog>
      <div class="dialog-button-container">
        <button id="showDialog">Report Seller</button>
      </div>
    <?php elseif (
       !Product::getProductQuantityAttribute($product)
    ): ?>
      <p><b>Out of stock!</b></p>
    <?php else: ?>
      <button>
        <a href="login.php">
          Login as a member to purchase
        </a>
      </button>
    <?php endif; ?>
  </div>
</div>

<?php if (
  Product::getProductDescriptionAttribute($product)
): ?>
  <div class="product-display-description-container">
    <h2>Product Description</h2>
    <p><?= Product::getProductDescriptionAttribute($product) ?></p>
  </div>
<?php endif; ?>


<!-- Display product details -->
<!-- TODO: Change these elements and make them presentable -->


<?php if ($reviews = Review::getReviews(Product::getProductIdAttribute($product))): ?>
  <div class="reviews-container">
    <h2>Reviews</h2>
    <?php foreach ($reviews as $review): ?>
      <div class="review-post-container">
        <div class="review-header">
          <div class="review-header-left">
            <img src="<?= User::getImagePath($review) ?>">
            <p class="review-author">
              <?= Review::getMemberName($review) ?>
            </p>
          </div>
          <div class="review-header-right">
            <p class="review-timestamp">
              <?= Review::getTimestamp($review) ?>
            </p>
            <p class="review-rating">
              <?= Review::getRating($review) ?>★ / 5★
            </p>
          </div>
        </div>
        <p class="review-comment">
          <?= Review::getComment($review) ?>
        </p>
        <?php 
          if (($type === 'member' 
            && Member::getUserIdAttribute($user) 
              === Review::getMemberId($review)
          ) || $type === 'admin' ): 
        ?>
          <div class="review-actions-container">
            <button
              class="review-actions-edit"
              value="<?= Review::getId($review) ?>"
            >
              Edit
            </button>
            <button
              class="review-actions-delete"
              value="<?= Review::getId($review) ?>"
            >
              Delete
            </button>
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
        <div class="form-input-container">
          <div>Rating</div>
          <div class="form-radio-option-container">
            <input type="radio" id="star1" name="rating" value="1" />
            <label for="star1" title="1 Star">★</label>
          </div>
          <div class="form-radio-option-container">
            <input type="radio" id="star2" name="rating" value="2" />
            <label for="star2" title="2 Stars">★★</label>
          </div>
          <div class="form-radio-option-container">
            <input type="radio" id="star3" name="rating" value="3" />
            <label for="star3" title="3 Stars">★★★</label>
          </div>
          <div class="form-radio-option-container">
            <input type="radio" id="star4" name="rating" value="4" />
            <label for="star4" title="4 Stars">★★★★</label>
          </div>
          <div class="form-radio-option-container">
            <input type="radio" id="star5" name="rating" value="5" checked>
            <label for="star5" title="5 Stars">★★★★★</label>
          </div>
        </div>
        <div class="form-input-container">
          <label for="comment">Comment</label>
          <textarea name="comment" id="comment" cols="30" rows="10"></textarea>
        </div>
        <div class="review-buttons-container">
          <button type="submit" name="submit" value="submit">Submit</button>
          <button value="cancel" id="cancel" formmethod="dialog">Cancel</button>
        </div>
      </form>
    </dialog>
    <dialog id="delete_review_dialog">
      <p>Do you wish to delete this review?</p>

      <form 
        action="scripts/_delete_review.php"
        method="POST"
      >
        <!-- The value of this is supplied in the JS -->
        <!-- There is a conflict in ID names so we append -->
        <!-- "_delete" to it -->
        <input 
          type="hidden" 
          name="review_id_delete"
          id="review_id_delete"
        >

        <div class="review-buttons-container">
          <button type="submit" name="submit" value="submit">Delete</button>
          <button value="cancel" id="cancel" formmethod="dialog">Cancel</button>
        </div>
      </form>
    </dialog>
  </div>
<?php endif; ?>

<script src="js/product.js"></script>

<?php Component\Footer(); ?>