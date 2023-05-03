<?php

require_once 'lib/require.php';
require_once 'lib/Product.php';


$query = filter_input(
  INPUT_GET,
  'search',
  FILTER_SANITIZE_SPECIAL_CHARS,
);

if (!$query) return false;

?>

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
