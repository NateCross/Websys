<?php

require_once 'lib/require.php';

Component\Header('Register');

?>

<h1>Register</h1>

<div class="form-container">
  <form action="scripts/_register.php" method="POST">
    <div class="form-input-container">
      <label for="Email">Email</label>
      <input type="email" name="email" id="email" required>
    </div>

    <div class="form-input-container">
      <label for="username">Username</label>
      <input type="text" name="username" id="username" required>
    </div>

    <div class="form-input-container">
      <label for="password">Password</label>
      <input type="password" name="password" id="password" required>
    </div>

    <div class="form-input-container">
      <label for="confirm_password">Confirm Password</label>
      <input type="password" name="confirm_password" id="confirm_password" required>
    </div>

    <div class="form-input-container">
      <label for="address">Address</label>
      <input type="text" name="address" id="address" required>
    </div>

    <div class="form-input-container">
      <label for="contact_number">Contact Number</label>
      <input type="text" name="contact_number" id="contact_number" required>
    </div>

    <div class="form-input-radio">
      <label for="member">Member</label>
      <input 
        type="radio" 
        name="type" 
        id="member" 
        checked
        value="Member"
      >
      <label for="seller">Seller</label>
      <input 
        type="radio" 
        name="type" 
        id="seller" 
        value="Seller"
      >
    </div>

    <div class="form-input-container">
      <input type="submit" name="submit" value="Submit">
    </div>
  </form>
</div>

<?php Component\Footer(); ?>