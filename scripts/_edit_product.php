<?php

require_once "../lib/require.php";
require_once "../lib/User.php";
require_once "../lib/Seller.php";

?>

<?php if (!isset($_POST['submit'])): ?>

<p>Invalid form. Please try again.</p>
<script type="module">
  import { redirect } from '../lib/utils.js';
  redirect('/', 3000);
</script>

<?php die(); endif; ?>

<?php
  [
    'name' => $name,
    'description' => $description,
    'quantity' => $quantity,
    'price' => $price,
    'category' => $category,
    'id' => $product_id,
  ] = filter_input_array(INPUT_POST, [
    'name' => FILTER_SANITIZE_SPECIAL_CHARS,
    'description' => FILTER_SANITIZE_SPECIAL_CHARS,
    'quantity' => FILTER_VALIDATE_INT,
    'price' => FILTER_VALIDATE_FLOAT,
    'category' => FILTER_SANITIZE_SPECIAL_CHARS,
  ]);
  $image = $_FILES['image'];
?>

<?php if (Seller::updateProduct(
  $product_id,
  $name, 
  $description, 
  $quantity, 
  $price, 
  $category,
)): ?>
  <p>Successfully added product.</p>
  <a href="/">Click to return</a>
<?php else: ?>
  <p>An error has occurred.</p>
  <a href="/">Click to return</a>
<?php endif; ?>