<?php

require_once "lib/require.php";
require_once "lib/User.php";
require_once "lib/Member.php";
require_once "lib/Seller.php";
require_once "lib/Admin.php";
require_once "lib/Product.php";

$type = User::getCurrentUserType();

if ($type === 'member')
  $user = Member::getCurrentUser();
else if ($type === 'seller')
  $user = Seller::getCurrentUser();
else if ($type === 'admin')
  $user = Admin::getCurrentUser();

$product_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_ENCODED);
if (!isset($product_id))
  Utils\redirect('../index.php');
$product = Product::getProducts($product_id)[0];
$seller = Product::getSellerById($product_id);

Component\Header('Edit Product');

?>

<!-- Check for errors -->
<?php if (!$user): ?>
  <p>No user. Please try again.</p>
  <script type="module">
    import { redirect } from './js/utils.js';
    redirect('/', 3000);
  </script>
  <?php die(); ?>
<?php elseif (!$product_id): ?>
  <script type="module">
    import { redirect } from './js/utils.js';
    redirect();
  </script>
  <?php die(); ?>
<?php elseif ($type !== 'admin'): ?>
  <?php if (!$type === 'seller'): ?>
    <p>Not a seller. Please try again.</p>
    <script type="module">
      import { redirect } from './js/utils.js';
      redirect('/', 3000);
    </script>
    <?php die(); ?>
  <?php elseif (
    User::getUserIdAttribute($user) 
    !== User::getUserIdAttribute($seller)
  ): ?>
    <p>Not the seller of this product. Please try again.</p>
    <script type="module">
      import { redirect } from './js/utils.js';
      redirect('/', 3000);
    </script>
    <?php die(); ?>
  <?php endif; ?>
<?php endif; ?>

<h1>Edit Product</h1>

<div class="form-container">
  <form 
    action="scripts/_edit_product.php" 
    method="post" 
  >
    <!-- Sends the id of the product -->
    <input 
      type="hidden" 
      name="product_id"
      value="<?= Product::getProductIdAttribute($product) ?>"
    >

    <div class="form-input-container">
      <label for="name">Name</label>
      <input 
        type="text" 
        name="name" 
        id="name" 
        value="<?= Product::getProductNameAttribute($product) ?>"
        required
      >
    </div>

    <div class="form-input-container">
    <label for="description">Description</label>
    <textarea 
      name="description" 
      id="description" 
      cols="30" 
      rows="10"
    ><?=Product::getProductDescriptionAttribute($product) ?></textarea>
    </div>

    <div class="form-input-container">
    <label for="category">Category</label>
    <input 
      type="text" 
      name="category" 
      id="category" 
      required
      value="<?= Product::getProductCategoryAttribute($product) ?>"
    >
    </div>

    <div class="form-input-container">
    <label for="quantity">Quantity</label>
    <input 
      type="number" 
      name="quantity" 
      id="quantity"
      min=0
      value="<?= Product::getProductQuantityAttribute($product) ?>"
      required
    >
    </div>

    <div class="form-input-container">
    <label for="price">Price in PHP</label>
    <input 
      type="number" 
      name="price" 
      id="price"
      min=1
      step="any"
      required
      value="<?= Product::getProductPriceAttribute($product) ?>"
    >
    </div>

    <div class="form-input-container">
      <input type="submit" name="submit" value="Submit">
    </div>
  </form>

</div>

<h2 class="product-edit-header">Edit Image</h2>
<div class="form-container">
  <form 
    action="scripts/_edit_product_image.php" 
    method="post" 
    enctype="multipart/form-data"
  >
    <!-- Sends the id of the product -->
    <input 
      type="hidden" 
      name="product_id"
      value="<?= Product::getProductIdAttribute($product) ?>"
    >

    <div class="form-input-container">
      <label for="image">Image</label>
      <input type="file" name="image" id="image" required>
    </div>

    <input type="submit" name="submit" value="Submit">
  </form>
</div>

<?php Component\Footer(); ?>