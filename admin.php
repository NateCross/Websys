<?php

require_once 'lib/require.php';

Component\Header("Admin Login");

?>

<h1>Admin Login</h1>

<div class="form-container">
  <form action="scripts/_login.php" method="POST">
    <input type="hidden" name="type" value="Admin">

    <div class="form-input-container">
      <label for="email">Email</label>
      <input type="email" name="email" id="email" required>
    </div>

    <div class="form-input-container">
      <label for="password">Password</label>
      <input type="password" name="password" id="password" required>
    </div>

    <div class="form-input-container">
      <input type="submit" name="submit" value="Submit">
    </div>
  </form>
</div>

<?php

Component\Footer();