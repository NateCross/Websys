<?php

require_once "../../../require/require.php";
require_once "../../../models/User.php";

$user = User::getCurrentUser();
$type = User::getCurrentUserType();

?>

<?php if (!$user): ?>
<p>No user. Please try again.</p>
<script type="module">
  import { redirect } from '../../utils.js';
  redirect('/', 1000);
</script>
<?php elseif (!$type === 'seller'): ?>
<p>Not a seller. Please try again.</p>
<script type="module">
  import { redirect } from '../../utils.js';
  redirect('/', 1000);
</script>
<?php else: ?>

<h1>Add Product</h1>

<form 
  action="add/_add.php" 
  method="post" 
  enctype="multipart/form-data"
>
  <label for="name">Name</label>
  <input type="text" name="name" id="name" required>

  <label for="description">Description</label>
  <textarea name="description" id="description" cols="30" rows="10"></textarea>

  <label for="quantity">Quantity</label>
  <input 
    type="number" 
    name="quantity" 
    id="quantity"
    min=0
    required
  >

  <label for="price">Price in PHP</label>
  <input 
    type="number" 
    name="price" 
    id="price"
    min=1
    step="any"
    required
  >

  <label for="image">Image</label>
  <input type="file" name="image" id="image" required>

  <input type="submit" name="submit" value="Submit">
</form>


<?php endif; ?>
