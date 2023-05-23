<?php

// This page handles account settings

require_once 'lib/require.php';
require_once 'lib/Session.php';
require_once 'lib/User.php';

$type = User::getCurrentUserType();

if ($type === 'member')
  $user = Member::getCurrentUser();
else if ($type === 'seller')
  $user = Seller::getCurrentUser();
else if ($type === 'admin')
  $user = Admin::getCurrentUser();

if (!$user)
  Utils\redirect('../login.php');

Component\Header(User::getUserNameAttribute($user));

?>

<h1>Hello, <?= User::getUserNameAttribute($user) ?></h1>

<h2>Edit Account Information</h2>
<form action="scripts/_edit_account.php" method="POST">
  <!-- Sends the user ID to the POST request -->
  <input 
    type="hidden" 
    name="user_id"
    value="<?= User::getUserIdAttribute($user) ?>"
  >

  <div class="form-input-container">
    <label for="email">Email</label>
    <input 
      type="email" 
      name="email" 
      id="email" 
      required
      value="<?= User::getUserEmailAttribute($user) ?>"
    >
  </div>

  <div class="form-input-container">
    <label for="username">Username</label>
    <input 
      type="text" 
      name="username" 
      id="username" 
      required
      value="<?= User::getUserNameAttribute($user) ?>"
    >
  </div>

  <div class="form-input-container">
    <label for="address">Address</label>
    <input 
      type="text" 
      name="address" 
      id="address" 
      required
      value="<?= User::getUserAddressAttribute($user) ?>"
    >
  </div>

  <div class="form-input-container">
    <label for="contact_number">Contact Number</label>
    <input 
      type="text" 
      name="contact_number" 
      id="contact_number" 
      required
      value="<?= User::getUserContactNumberAttribute($user) ?>"
    >
  </div>

  <div class="form-input-container">
    <label for="password">Password</label>
    <input 
      type="password" 
      name="password" 
      id="password" 
    >
  </div>

  <div class="form-input-container">
    <input type="submit" name="submit" value="Submit">
  </div>
</form>

<h2>Edit Profile Picture</h2>

<img src="<?= User::getImagePath($user) ?>">

<form 
  action="scripts/_edit_account_image.php"
  method="POST"
  enctype="multipart/form-data"
>
  <input 
    type="hidden" 
    name="user_id"
    value="<?= User::getUserIdAttribute($user) ?>"
  >

  <div class="form-input-container">
    <input type="file" name="image" id="image">
  </div>

  <div class="form-input-container">
    <input type="submit" name="submit" value="Submit">
  </div>
</form>

<h2>Delete User</h2>

<form action="scripts/_delete_user.php" method="POST">
  <input 
    type="hidden" 
    name="user_id"
    value="<?= User::getUserIdAttribute($user) ?>"
  >

  <div class="form-input-container">
    <input type="submit" name="submit" value="Delete">
  </div>
</form>

<?php

Component\Footer();

?>