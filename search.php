<?php

require_once 'lib/require.php';
require_once 'lib/Product.php';
require_once 'lib/Seller.php';


$query = filter_input(
  INPUT_GET,
  'search',
  FILTER_SANITIZE_SPECIAL_CHARS,
);
$type = filter_input(
  INPUT_GET,
  'type',
  FILTER_SANITIZE_SPECIAL_CHARS,
);

if (!$query) return false;

Component\Header("Search: $query");

?>

<?php if ($type === "product"): ?>

  <?php if (!$products = Product::searchProduct($query)): ?>
    <p>No products found.</p>
  <?php endif; ?>

  <ul>
    <?php foreach($products as $product): ?>
      <li>
        <a 
          href="product.php?id=<?= Product::getProductIdAttribute($product) ?>"
        >
          <!-- TODO: Adjust size through CSS  -->
          <img 
            src="<?= Product::getImagePath($product); ?>" 
          >
          <?= Product::getProductNameAttribute($product); ?>
          </a>
      </li>
    <?php endforeach; ?>
  </ul>
<?php elseif ($type === 'seller'): ?>
  <?php if (!$sellers = Seller::searchSeller($query)): ?>
    <p>No sellers found.</p>
  <?php endif; ?>

  <ul>
    <?php foreach($sellers as $seller): ?>
      <li>
        <a 
          href="seller.php?id=<?= Seller::getUserIdAttribute($seller) ?>"
        >
          <!-- TODO: Adjust size through CSS  -->
          <!-- <img 
            src="<?= Product::getImagePath($product); ?>" 
          > -->
          <?= Seller::getUserNameAttribute($seller); ?>
          </a>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>

<?php Component\Footer(); ?>