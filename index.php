<?php

require_once 'lib/require.php';
require_once 'lib/User.php';
require_once 'lib/Product.php';

Component\Header('Home');

?>

<?php if ($products = Product::getProducts()) { ?>
  <ul class="product-list-container">
    <?php foreach ($products as $product) : ?>

      <li>
        <a href="product.php?id=<?= Product::getProductIdAttribute($product) ?>">
          <!-- TODO: Adjust size through CSS  -->
          <img src="<?= Product::getImagePath($product); ?>">
          <?= Product::getProductNameAttribute($product); ?>
        </a>
      </li>

    <?php endforeach; ?>
  </ul>

<?php } ?>

<script src='js/index.js'></script>

<?php Component\Footer(); ?>