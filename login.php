<?php

require_once 'lib/require.php';

Component\Header('Login');

?>

<h1>Login</h1>

<div class="form-container">
  <form action="scripts/_login.php" method="POST">
    <div class="form-input-container">
      <label for="email">Email</label>
      <input type="email" name="email" id="email" required>
    </div>

    <div class="form-input-container">
      <label for="password">Password</label>
      <input type="password" name="password" id="password" required>
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

<?php

Component\Footer();