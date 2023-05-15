<?php

require_once '../lib/require.php';
require_once '../lib/User.php';
require_once '../lib/Member.php';
require_once '../lib/Seller.php';
require_once '../lib/Admin.php';

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
    'address' => $address,
    'contact_number' => $contact_number,
  ] = filter_input_array(INPUT_POST, [
    'user_id' => FILTER_SANITIZE_SPECIAL_CHARS,
    'email' => FILTER_VALIDATE_EMAIL,
    'username' => FILTER_SANITIZE_SPECIAL_CHARS,
    'password' => FILTER_SANITIZE_SPECIAL_CHARS,
    'address' => FILTER_SANITIZE_SPECIAL_CHARS,
    'contact_number' => FILTER_SANITIZE_SPECIAL_CHARS,
  ]);
?>

<?php if (($type === 'member' && Member::updateUser(
  $user_id,
  $email,
  $username,
  $password,
  $address,
  $contact_number,
)) || ($type === 'seller' && Seller::updateUser(
  $user_id,
  $email,
  $username,
  $password,
  $address,
  $contact_number,
)) || ($type === 'admin' && Admin::updateUser(
  $user_id,
  $email,
  $username,
  $password,
  $address,
  $contact_number,
)))
  Utils\redirectPage("Successfully edited user");
else
  Utils\redirectPage("ERROR: Unable to edit user");