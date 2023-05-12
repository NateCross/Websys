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
        <?= $price = Product::getProductPriceAttribute($product) ?>
      </td>
      <td>
        <form 
          action="scripts/_edit_cart_item.php"
          method="POST"
        >
          <input 
            type="number" 
            name="quantity_purchased" 
            id="quantity_purchased"
            value="<?= $quantity_purchased = Cart::getProductQuantityPurchased($product) ?>"
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
        ₱<?= $price * $quantity_purchased ?>
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
  Subtotal: ₱<?= $subtotal ?>
</div>

<form 
  action="scripts/_place_order.php"
  method="POST"
>
  <input type="submit" name="submit" value="Place Order">
</form>

<?php else: ?>

<p>No items in cart.</p>

<?php endif; ?>

<?php Component\Footer(); ?>