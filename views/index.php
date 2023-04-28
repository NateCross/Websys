<?php

require_once '../require/require.php';

if ($user = Session::get('user')) {
  echo "Hello, " . $user['name'] . "<br>";
  echo "Currently logged in as a: " . Session::get('type') . "<br>";
}

?>

<a href="/login">Login</a>

<a href="/register">Register</a>

<?php if (Session::has('user')): ?>
  <button id="logout">Logout</button>
<?php endif; ?>

<script src='index.js'></script>