<?php

require_once 'lib/require.php';
require_once 'lib/Product.php';
require_once 'lib/Seller.php';
require_once 'lib/Review.php';


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

  <?php if ($products = Product::searchProduct($query)) { ?>
    <ul class="product-list-container">
      <?php foreach ($products as $product) : ?>
        <li>
          <a href="product.php?id=<?= Product::getProductIdAttribute($product) ?>">
            <div class="product-image-container">
              <img src="<?= Product::getImagePath($product); ?>">
            </div>
            <div class="product-details-container">
              <p class="product-details-name">
                <?= Product::getProductNameAttribute($product); ?>
              </p>
              <p>
                <?= Product::getProductCategoryAttribute($product); ?>
              </p>
              <p>
                <?= Utils\formatCurrency(Product::getProductPriceAttribute($product)); ?>
              </p>
              <?php $rating = Review::getAverageRating(Product::getProductIdAttribute($product)); ?>
              <?php if ($rating): ?>
              <p>
                <?= $rating ?>★
              </p>
              <?php endif; ?>
            </div>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php } else { ?>
    <p>No products found.</p>
  <?php } ?>
<?php elseif ($type === 'seller'): ?>
  <?php if (!$sellers = Seller::searchSeller($query)): ?>
    <p>No sellers found.</p>
  <?php endif; ?>

  <ul class="product-list-container">
    <?php foreach($sellers as $seller): ?>
      <li>
        <a 
          href="seller.php?id=<?= Seller::getUserIdAttribute($seller) ?>"
        >
          <!-- TODO: Adjust size through CSS  -->
          <div class="product-image-container">
            <img 
              src="<?= Seller::getImagePath($seller); ?>" 
            >
          </div>
          <div class="product-details-container product-details-name">
            <?= Seller::getUserNameAttribute($seller); ?>
          </div>
          </a>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>

<?php Component\Footer(); ?>