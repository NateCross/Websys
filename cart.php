<?php

require_once 'lib/require.php';
require_once 'lib/Cart.php';
require_once 'lib/Product.php';
require_once 'lib/Member.php';

if (!$member = Member::getCurrentUser())
  Utils\redirect('index.php');

$cart = Cart::getCart();
if ($cart) rsort($cart);

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
            value="<?= $index ?>"
          >
          <input type="submit" value="Update Quantity" name="submit">
        </form>
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
<div class="subtotal-container">
  Subtotal: <?= Utils\formatCurrency($subtotal) ?>
</div>

<div class="checkout-container">
  <button id="showDialog">Checkout</button>
</div>

<!-- Modal that displpays when submit order is clicked -->
<dialog id="submitOrderDialog">
  <h2>Checkout</h2>
  <p class="submit-order-text-container">
    Your order for <?= count($cart) ?> 
    <?= count($cart) > 1 ? "items" : "item" ?>
    amounts to <?= Utils\formatCurrency($subtotal) ?>.
  </p>
  <form 
    action="scripts/_place_order.php"
    method="POST"
  >
    <div class="bank-details-container">
      <div class="place-order-bank">
        <label for="bank">Bank</label>
        <select name="bank" id="bank">
          <option value="bdo">BDO</option>
          <option value="bpi">BPI</option>
          <option value="other">Other</option>
        </select>
        <input type="text" name="bank_other" id="bank_other" hidden>
      </div>

      <div class="place-order-owner">
        <label for="owner">Owner</label>
        <input type="text" name="owner" id="owner" required>
      </div>

      <div class="place-order-cvv">
        <label for="cvv">CVV</label>
        <input type="text" name="cvv" id="cvv" required>
      </div>

      <div class="place-order-card-number">
        <label for="card_number">Card Number</label>
        <input type="text" name="card_number" id="card_number" required>
      </div>

      <div class="place-order-expiration-date">
        <label>Expiration Date</label>
        <select name="expiration_date_month" id="expiration_date_month">
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
        <input type="number" min="00" max="99" name="expiration_date_year" id="expiration_date_year" required>
      </div>
    </div>
    <div class="shipping-details-container">
      <div class="shipping-details-address">
        <label for="address">Address</label>
        <input 
          type="text" 
          name="address" 
          id="address"
          value="<?= Member::getUserAddressAttribute($member) ?>"
          required
        >
      </div>
      <div class="shipping-details-contact-number">
        <label for="contact_number">Contact Number</label>
        <input 
          type="text" 
          name="contact_number" 
          id="contact)number"
          value="<?= Member::getUserContactNumberAttribute($member) ?>"
          required
        >
      </div>
    </div>

    <input type="submit" name="submit" value="Place Order">
    <button value="cancel" id="cancel" formmethod="dialog">Cancel</button>
  </form>
</dialog>

<script src="js/cart.js"></script>

<?php else: ?>

<p>No items in cart.</p>

<?php endif; ?>

<?php Component\Footer(); ?>