<?php

if (!isset($_POST['submit'])) return;

require_once "../lib/require.php";
require_once "../lib/Member.php";
require_once "../lib/Seller.php";
require_once "../lib/Admin.php";

$email = filter_input(
  INPUT_POST,
  'email',
  FILTER_VALIDATE_EMAIL,
);
$username = filter_input(
  INPUT_POST,
  'username',
  FILTER_SANITIZE_SPECIAL_CHARS,
);
$password = filter_input(
  INPUT_POST,
  'password',
  FILTER_SANITIZE_SPECIAL_CHARS,
);
$confirm_password = filter_input(
  INPUT_POST,
  'confirm_password',
  FILTER_SANITIZE_SPECIAL_CHARS,
);
$type = filter_input(
  INPUT_POST,
  'type',
  FILTER_SANITIZE_SPECIAL_CHARS,
);

?>

<?php if ($password !== $confirm_password): ?>
  <p>Passwords do not match. Redirecting to register page...</p>
  <script type="module">
    import { redirect } from '../js/utils.js';
    redirect('/register.php', 3000);
  </script>
  <?php die(); ?>
<?php endif; ?>

<?php

if ($type === "Member") {
  if (Member::register($email, $username, $password))
    header('Location: /');
} else if ($type === "Seller") {
  if (Seller::register($email, $username, $password))
    header('Location: /');
} else if ($type === "Admin") {
  if (Admin::register($email, $username, $password))
    Utils\redirect('../admin-panel.php');
}

echo "Unable to create account. Please try again.";

?>

<script type="module">
  import {
    redirect
  } from '../js/utils.js';
  redirect('/', 3000);
</script>