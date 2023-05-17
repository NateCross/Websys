<?php

require_once 'lib/require.php';
require_once 'lib/Seller.php';
require_once 'lib/Review.php';

if (!$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT))
  Utils\redirectPage("No ID");

if (!$seller = Seller::getUserViaId($id))
  Utils\redirectPage("No Seller");

$products = Seller::getProducts($id);

Component\Header("Seller: " . Seller::getUserNameAttribute($seller));

?>

<h1><?= Seller::getUserNameAttribute($seller) ?></h1>

<?php if ($products) { ?>
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
              <?= number_format($rating, 2); ?>★
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

<?php Component\Footer(); ?>