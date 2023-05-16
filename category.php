<?php

require_once 'lib/require.php';
require_once 'lib/Category.php';
require_once 'lib/Product.php';
require_once 'lib/Review.php';

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_ENCODED);

if (!$id)
  Utils\redirect('index.php');

$category = Category::getCategories($id);
$products = Category::getProducts($id);

Component\Header(Category::getName($category));

?>

<h1><?= Category::getName($category); ?></h1>

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