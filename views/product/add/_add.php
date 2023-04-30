<?php

require_once "../../../require/require.php";
require_once "../../../models/User.php";

?>

<?php if (!isset($_FILES['image']) || !isset($_POST['submit'])): ?>

<p>Invalid form. Please try again.</p>
<script type="module">
  import { redirect } from '../../utils.js';
  redirect('/', 1000);
</script>

<?php else: {

  $inputs = filter_input_array(INPUT_POST, [
    'name' => FILTER_SANITIZE_ENCODED,
    'description' => FILTER_SANITIZE_ENCODED,
    'quantity' => FILTER_VALIDATE_INT,
    'price' => FILTER_VALIDATE_FLOAT,
  ]);
  $image = $_FILES['image'];

  var_dump($inputs);
  var_dump($image);

} endif; ?>