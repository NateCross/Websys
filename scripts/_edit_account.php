<?php

require_once '../lib/require.php';
require_once '../lib/User.php';
require_once '../lib/Member.php';
require_once '../lib/Seller.php';

$type = User::getCurrentUserType();

?>

<?php if (!isset($_POST['submit'])): ?>

<p>Invalid form. Redirecting to previous page... </p>
<script type="module">
  import { redirect } from '../js/utils.js';
  redirect('/account.php', 3000);
</script>

<?php die(); endif; ?>

<?php
  [
    'user_id' => $user_id,
    'email' => $email,
    'username' => $username,
    'password' => $password,
  ] = filter_input_array(INPUT_POST, [
    'user_id' => FILTER_SANITIZE_SPECIAL_CHARS,
    'email' => FILTER_VALIDATE_EMAIL,
    'username' => FILTER_SANITIZE_SPECIAL_CHARS,
    'password' => FILTER_SANITIZE_SPECIAL_CHARS,
  ]);
?>

<?php if (($type === 'member' && Member::updateUser(
  $user_id,
  $email,
  $username,
  $password,
)) || ($type === 'seller' && Seller::updateUser(
  $user_id,
  $email,
  $username,
  $password,
))): ?>
  <p>Successfully edited user.</p>
  <a href="/">Click to return</a>
<?php else: ?>
  <p>An error has occurred.</p>
  <a href="/">Click to return</a>
<?php endif; ?>