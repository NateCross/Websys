<?php

// This page handles account settings

require_once 'lib/require.php';
require_once 'lib/Session.php';
require_once 'lib/User.php';

$user = User::getCurrentUser();
$type = User::getCurrentUserType();

?>

<!-- Check for errors -->
<?php if (!$user):?>
  <script type="module">
    import { redirect } from 'lib/utils.js';
    redirect('login.php');
  </script>
  <?php die(); ?>
<?php endif; ?>

<form action="scripts/_edit_account.php" method="POST">
  <!-- Sends the user ID to the POST request -->
  <input 
    type="hidden" 
    name="user_id"
    value="<?= User::getUserIdAttribute($user) ?>"
  >

  <label for="email">Email</label>
  <input 
    type="email" 
    name="email" 
    id="email" 
    required
    value="<?= User::getUserEmailAttribute($user) ?>"
  >

  <label for="username">Username</label>
  <input 
    type="text" 
    name="username" 
    id="username" 
    required
    value="<?= User::getUserNameAttribute($user) ?>"
  >

  <label for="password">Password</label>
  <input 
    type="password" 
    name="password" 
    id="password" 
    required
  >

  <input type="submit" name="submit" value="Submit">
</form>