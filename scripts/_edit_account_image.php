<?php

require_once "../lib/require.php";
require_once "../lib/User.php";
require_once "../lib/Member.php";
require_once "../lib/Seller.php";

?>

<?php if (!isset($_POST['submit'])): ?>

<p>Invalid form. Please try again.</p>
<script type="module">
  import { redirect } from '../js/utils.js';
  redirect('/', 3000);
</script>

<?php die(); endif; ?>

<?php
  $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_SPECIAL_CHARS);
  $image = $_FILES['image'];

  $type = User::getCurrentUserType();

  if ($type === 'member')
    $user = Member::getCurrentUser();
  else if ($type === 'seller')
    $user = Seller::getCurrentUser();
  else if ($type === 'admin')
    $user = Admin::getCurrentUser();
?>

<!-- Check for errors -->
<?php if (!$user): ?>
  <p>No user. Please try again.</p>
  <script type="module">
    import { redirect } from '../js/utils.js';
    redirect('/', 3000);
  </script>
  <?php die(); ?>
<?php elseif (!$image): ?>
  <script type="module">
    import { redirect } from '../js/utils.js';
    redirect();
  </script>
  <?php die(); ?>
<?php endif; ?>

<?php if (($type === 'member' && Member::updateUserImage(
  $user_id,
  $image,
)) || ($type === 'seller' && Seller::updateUserImage(
  $user_id,
  $image,
)))
  Utils\redirectPage("Successfully edited image");
else
  Utils\redirectPage("ERROR: Unable to edit account image");