<?php

// This page shows the purchases of a member

require_once 'lib/require.php';
require_once 'lib/Member.php';
require_once 'lib/Product.php';

$type = Member::getCurrentUserType();
if (!$type || $type !== 'member')
  Utils\redirect('index.php');
$user = Member::getCurrentUser();

Component\Header('Purchases');

?>

<h1>Purchases</h1>

<?php $products = Member::getBillsWithProducts(Member::getUserIdAttribute($user)) ?>

<?php if ($products): ?>

<table>
  <tr>
    <th>Bill ID</th>
    <th>Product ID</th>
    <th>Product Name</th>
    <th>Image</th>
    <th>Price</th>
    <th>Quantity Purchased</th>
    <th>Total</th>
    <th>Actions</th>
  </tr>
  <?php foreach($products as $product): ?>
    <tr>
      <td><?= $product['bill_id'] ?></td>
      <td>
        <a href="product.php?id=<?= $product['id'] ?>">
          <?= $product['id'] ?>
        </a>
      </td>
      <td>
        <a href="product.php?id=<?= $product['id'] ?>">
          <?= $product['name'] ?>
        </a>
      </td>
      <td>
        <a href="product.php?id=<?= $product['id'] ?>">
          <img src="<?= Product::getImagePath($product) ?>">
        </a>
      </td>
      <td><?= Utils\formatCurrency($product['price']) ?></td>
      <td><?= $product['quantity_purchased'] ?></td>
      <td><?= Utils\formatCurrency($product['price'] * $product['quantity_purchased']) ?></td>
      <td>
        <button
          class="submit_review"
          value="<?= Product::getProductIdAttribute($product) ?>"
        >
          Submit Review
        </button>
      </td>
    </tr>
  <?php endforeach; ?>
</table>

<dialog id="review_dialog">
  <form 
    action="scripts/_review_product.php"
    method="POST"
  >
    <input 
      type="hidden" 
      name="member_id" 
      id="member_id"
      value="<?= Member::getUserIdAttribute($user) ?>"
    >
    <!-- Change the value of product_id in the JS -->
    <input 
      type="hidden" 
      name="product_id" 
      id="product_id"
    >

    <div class="form-input-container">
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
    <div class="form-input-container">
      <label for="comment">Comment</label>
      <textarea name="comment" id="comment" cols="30" rows="10"></textarea>
    </div>
    <div>
      <button type="submit" name="submit" value="submit">Submit</button>
      <button value="cancel" id="cancel" formmethod="dialog">Cancel</button>
    </div>
  </form>
</dialog>

<script src="js/purchases.js"></script>

<?php else: ?>

<p>You have no purchases yet.</p>

<?php endif; ?>

<?php

Component\Footer();