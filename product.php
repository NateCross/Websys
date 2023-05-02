<?php if (!$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_ENCODED)): ?>
  <script type="module">
    import { redirect } from 'lib/utils.js';
    redirect();
  </script>
  <?php die(); ?>
<?php endif; ?>

<?php

require_once 'lib/require.php';
require_once 'lib/Product.php';

$product = Product::getProducts($id)[0];
$seller = Product::getSeller($product['seller_id']);

?>

<?php if(!$product): ?>
  <p>Product not found. Redirecting to home page...</p>
  <script type="module">
    import { redirect } from 'lib/utils.js';
    redirect('/', 3000);
  </script>
<?php die(); endif; ?>

<h1><?= $product['name']; ?></h1>
<p>Seller: <?= Product::getSeller($product['seller_id']) ?></p>