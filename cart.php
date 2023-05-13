<?php

require_once 'lib/require.php';
require_once 'lib/Cart.php';
require_once 'lib/Product.php';

Component\Header('Cart');

?>

<h1>Cart</h1>

<?php if ($cart = Cart::getCart()): ?>
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

<dialog id="submitOrderDialog">
  <h2>Checkout</h2>
  <p class="submit-order-text-container">
    Your order for <?= count($cart) ?> 
    <?= count($cart) ? "items" : "item" ?>
    amounts to ₱<?= $subtotal ?>.
  </p>
  <form 
    action="scripts/_place_order.php"
    method="POST"
  >
    <input type="submit" name="submit" value="Place Order">
  </form>
</dialog>


<script src="js/cart.js"></script>

<?php else: ?>

<p>No items in cart.</p>

<?php endif; ?>

<?php Component\Footer(); ?>