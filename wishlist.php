<?php

require_once 'lib/Database.php';
require_once 'lib/require.php';
require_once 'lib/Member.php';
require_once 'lib/Product.php';
require_once 'lib/Wishlist.php';

$type = User::getCurrentUserType();
if ($type !== 'member')
  Utils\redirect('index.php');

if (!$member = Member::getCurrentUser())
  Utils\redirect('index.php');

$wishlist = Wishlist::getWishlist($member['id']);
if ($wishlist) rsort($wishlist);

Component\Header('Wishlist');

?>

<h1>Wishlist</h1>

<?php if ($wishlist): ?>


<table>
  <tr>
    <th>Product</th>
    <th>Image</th>
    <th>Price</th>
    <th>Actions</th>
  </tr>
  <?php $subtotal = 0; ?>
  <?php foreach($wishlist as $index => $product): ?>
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
        <div class="cart-delete-container">
          <form 
            action="scripts/_delete_wishlist_item.php"
            method="POST"
          >
            <input 
              type="hidden" 
              name="index" 
              value="<?= $product['wishlist_id'] ?>"
            >
            <input type="submit" value="Remove From Wishlist" name="submit">
          </form>
        </div>
      </td>
    </tr>
  <?php endforeach; ?>
  
</table>
<?php else: ?>

<p>Wishlist is empty.</p>

<?php endif; ?>