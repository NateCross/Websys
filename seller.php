<?php

require_once 'lib/require.php';
require_once 'lib/Seller.php';

if (!$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT))
  ErrorHandler::handleError("No ID");

if (!$seller = Seller::getUserViaId($id))
  ErrorHandler::handleError("No Seller");

$products = Seller::getProducts($id);

Component\Header("Seller: " . Seller::getUserNameAttribute($seller));

?>

<h1><?= Seller::getUserNameAttribute($seller) ?></h1>

<!-- For some reason, when the results are empty, it returns
an array of a single value in which all fields are null
Thus, we check if the value in this array exists instead
of just the array -->
<?php if (!$products[0]['id']): ?>
  <p>No products for this seller.</p>
<?php die(); endif; ?>

<ul class="product-list-container">
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

<?php Component\Footer(); ?>