<?php

require_once "lib/require.php";
require_once "lib/User.php";

$type = User::getCurrentUserType();

if ($type === 'member')
  $user = Member::getCurrentUser();
else if ($type === 'seller')
  $user = Seller::getCurrentUser();
else if ($type === 'admin')
  $user = Admin::getCurrentUser();

Component\Header('Add Product');

?>

<?php 

if (!$user)
  Utils\redirect("../index.php");
else if (!$type === 'seller')
  Utils\redirect("../index.php");

?>

<h1>Add Product</h1>

<div class="form-container">
  <form 
    action="scripts/_add_product.php" 
    method="post" 
    enctype="multipart/form-data"
  >
    <div class="form-input-container">
      <label for="name">Name</label>
      <input type="text" name="name" id="name" required>
    </div>

    <div class="form-input-container">
      <label for="description">Description</label>
      <textarea name="description" id="description" cols="30" rows="10"></textarea>
    </div>

    <!-- <label for="category">Category</label>
    <input type="text" name="category" id="category" required> -->
    <div class="form-input-container">
      <label for="category">Category</label>
      <select name="category" id="category">
        <option value="Board Games">Board Games</option>
        <option value="Gaming Accessories">Gaming Accessories</option>
        <option value="Technology">Technology</option>
      </select>
    </div>

    <div class="form-input-container">
      <label for="quantity">Quantity</label>
      <input 
        type="number" 
        name="quantity" 
        id="quantity"
        min=0
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
      >
    </div>

    <div class="form-input-container">
      <label for="image">Image</label>
      <input type="file" name="image" id="image" required>
    </div>

    <div class="form-input-container">
      <input type="submit" name="submit" value="Submit">
    </div>
  </form>
</div>

<?php Component\Footer(); ?>