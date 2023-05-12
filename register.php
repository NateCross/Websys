<?php

require_once 'lib/require.php';

?>

<form action="scripts/_register.php" method="POST">
  <label for="Email">Email</label>
  <input type="email" name="email" id="email" required>

  <label for="username">Username</label>
  <input type="text" name="username" id="username" required>

  <label for="password">Password</label>
  <input type="password" name="password" id="password" required>

  <label for="confirm_password">Confirm Password</label>
  <input type="password" name="confirm_password" id="confirm_password" required>

  <label for="address">Address</label>
  <input type="text" name="address" id="address" required>

  <label for="contact_number">Contact Number</label>
  <input type="text" name="contact_number" id="contact_number" required>

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

  <input type="submit" name="submit" value="Submit">
</form>