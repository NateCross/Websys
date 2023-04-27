<?php

require_once '../../require/require.php';

?>

<form action="/register/_register.php" method="POST">

  <label for="username">Username</label>
  <input type="text" name="username" id="username" required>

  <label for="password">Password</label>
  <input type="password" name="password" id="password" required>

  <input type="submit" name="submit" value="submitted">

</form>