<?php

require_once 'lib/require.php';
require_once 'lib/Cart.php';
require_once 'lib/Product.php';
require_once 'lib/Member.php';

if (!$member = Member::getCurrentUser())
  Utils\redirect('index.php');

$cart = Cart::getCart();
if ($cart) rsort($cart);

$coupon_code = Session::get('coupon_code') ? Session::get('coupon_code')[0] : null;

Component\Header('Cart');

?>

<h1>Cart</h1>

<?php if ($cart): ?>
<div class="clear-cart-container">
  <form 
    action="scripts/_clear_cart.php"
    method="POST"
  >
    <input type="submit" name="submit" value="Clear Cart">
  </form>
</div>
<table>
  <tr>
    <th>Product</th>
    <th>Image</th>
    <th>Price</th>
    <th>Quantity</th>
    <th>Total</th>
    <th>Actions</th>
  </tr>
  <?php $subtotal = 0; ?>
  <?php foreach($cart as $index => $product): ?>
    <tr>
      <td>
        <a href="product.php?id=<?= Product::getProductIdAttribute($product) ?>">
          <?= Product::getProductNameAttribute($product) ?>
        </a>
      </td>
      <td>
        <a href="product.php?id=<?= Product::getProductIdAttribute($product) ?>">
          <img 
            src="<?= Product::getImagePath($product) ?>"
          >
        </a>
      </td>
      <td>
        <?php $price = Product::getProductPriceAttribute($product); ?>
        <?= Utils\formatCurrency($price); ?>
      </td>
      <td>
        <p>In stock: <?= Product::getProductQuantityAttribute($product) ?></p>
        <div class="cart-quantity-container">
          <form 
            action="scripts/_edit_cart_item.php"
            method="POST"
          >
            <input 
              type="number" 
              name="quantity_purchased" 
              id="quantity_purchased"
              value="<?= $quantity_purchased = Cart::getProductQuantityPurchased($product) ?>"
              min="1"
              max="<?= Product::getProductQuantityAttribute($product) ?>"
            >
            <input 
              type="hidden" 
              name="index" 
              value="<?= count($cart) - 1 - $index ?>"
            >
            <input type="submit" value="Update Quantity" name="submit">
          </form>
        </div>
      </td>
      <td>
        <?php $subtotal += $price * $quantity_purchased ?>
        <?= Utils\formatCurrency($price * $quantity_purchased) ?>
      </td>
      <td>
        <div class="cart-delete-container">
          <form 
            action="scripts/_delete_cart_item.php"
            method="POST"
          >
            <input 
              type="hidden" 
              name="index" 
              value="<?= $index ?>"
            >
            <input type="submit" value="Remove Item" name="submit">
          </form>
        </div>
      </td>
    </tr>
  <?php endforeach; ?>
</table>

<!-- Display the subtotal in frontend -->
<!-- But it will be recalculated in backend so don't pass this -->
  <td>
    <form action="scripts/_search_coupon.php" method="POST">
      <input type="text" name="coupon_code" required>
        
      </input>
      <input type="submit" name="search_coupon" value="Use Voucher" onclick="voucherSuccess"> </input>
    </form>
  </td>
<?php if($coupon_code): ?>
<div class="subtotal-container">
  Subtotal: <?= Utils\formatCurrency($subtotal - ($subtotal * $coupon_code['discount'] / 100)) ?>
</div>
<?php else: ?>
<div class="subtotal-container">
  Subtotal: <?= Utils\formatCurrency($subtotal) ?>
</div>
<?php endif; ?>

<div class="checkout-container">
  <button id="showDialog">Checkout</button>
</div>

<!-- Modal that displays when submit order is clicked -->
<dialog id="submitOrderDialog">
  <h2>Checkout</h2>
  <?php if (!$coupon_code): ?>
    <p class="submit-order-text-container">
      Your order for <?= count($cart) ?> 
      <?= count($cart) > 1 ? "items" : "item" ?>
      amounts to <?= Utils\formatCurrency($subtotal) ?>.
    </p>
  <?php else: ?>
    <p class="submit-order-text-container">
      Your order for <?= count($cart) ?> 
      <?= count($cart) > 1 ? "items" : "item" ?>
      amounts to <?= Utils\formatCurrency($subtotal - ($subtotal * $coupon_code['discount'] / 100)) ?>.
    </p>
  <?php endif; ?>
  <form 
    action="scripts/_place_order.php"
    method="POST"
  >
    <input 
      type="hidden" 
      name="coupon_id"
      value="<?= $coupon_code['coupon_id'] ?>"
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
          value="<?= Member::getUserAddressAttribute($member) ?>"
          required
        >
      </div>
      <div class="form-input-container">
        <label for="contact_number">Contact Number</label>
        <input 
          type="text" 
          name="contact_number" 
          id="contact_number"
          value="<?= Member::getUserContactNumberAttribute($member) ?>"
          required
        >
      </div>
    </div>

    <div class="form-submit-container">
      <input type="submit" name="submit" value="Place Order">
      <button value="cancel" id="cancel" formmethod="dialog">Cancel</button>
    </div>
  </form>
</dialog>

<script src="js/cart.js"></script>
<script> 
  function voucherSuccess(){
    alert("Your Voucher was successfully applied!");
  }
</script>

<?php else: ?>

<p>No items in cart.</p>

<?php endif; ?>

<?php Component\Footer(); ?>