<?php

require_once 'lib/require.php';

?>

<h1>Admin Login</h1>

<form action="scripts/_login.php" method="POST">
  <input type="hidden" name="type" value="Admin">

  <label for="email">Email</label>
  <input type="email" name="email" id="email" required>

  <label for="password">Password</label>
  <input type="password" name="password" id="password" required>

  <input type="submit" name="submit" value="Submit">
</form>
