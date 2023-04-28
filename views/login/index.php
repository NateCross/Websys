<?php

require_once '../../require/require.php';

?>

<form action="/login/_login.php" method="POST">
  <label for="email">Email</label>
  <input type="email" name="email" id="email" required>

  <label for="password">Password</label>
  <input type="password" name="password" id="password" required>

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
