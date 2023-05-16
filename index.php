<?php

require_once 'lib/require.php';
require_once 'lib/User.php';
require_once 'lib/Product.php';
require_once 'lib/Review.php';

Component\Header('Home');

?>

<?php if ($products = Product::getProducts()) { ?>
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

<script src='js/index.js'></script>

<?php Component\Footer(); ?>