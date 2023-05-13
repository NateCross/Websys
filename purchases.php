<?php

// This page shows the purchases of a member

require_once 'lib/require.php';
require_once 'lib/Member.php';
require_once 'lib/Product.php';

$type = Member::getCurrentUserType();
if (!$type || $type !== 'member')
  Utils\redirect('index.php');
$user = Member::getCurrentUser();

Component\Header('Purchases');

?>

<h1>Purchases</h1>

<?php $products = Member::getBillsWithProducts(Member::getUserIdAttribute($user)) ?>

<?php var_dump($products) ?>

<?php if ($products): ?>

<table>
  <tr>
    <th>Bill ID</th>
    <th>Product ID</th>
    <th>Name</th>
    <th>Image</th>
    <th>Price</th>
    <th>Quantity Purchased</th>
    <th>Total</th>
    <th>Actions</th>
  </tr>
  <?php foreach($products as $product): ?>
    <tr>
      <td><?= $product['bill_id'] ?></td>
      <td>
        <a href="product.php?id=<?= $product['id'] ?>">
          <?= $product['id'] ?>
        </a>
      </td>
      <td>
        <a href="product.php?id=<?= $product['id'] ?>">
          <?= $product['name'] ?>
        </a>
      </td>
      <td>
        <a href="product.php?id=<?= $product['id'] ?>">
          <img src="<?= Product::getImagePath($product) ?>">
        </a>
      </td>
      <td><?= Utils\formatCurrency($product['price']) ?></td>
      <td><?= $product['quantity_purchased'] ?></td>
      <td><?= Utils\formatCurrency($product['price'] * $product['quantity_purchased']) ?></td>
      <td>Insert Review Code here</td>
    </tr>
  <?php endforeach; ?>

</table>

<?php else: ?>

<p>You have no purchases yet.</p>

<?php endif; ?>

<?php

Component\Footer();