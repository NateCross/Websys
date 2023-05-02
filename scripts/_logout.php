<?php

require_once "../lib/require.php";
require_once "../lib/User.php";

$logout = User::logout();

?>

<?php if ($logout): ?>

<p>Successfully logged out. <a href="/">Click to return</a></p>

<?php else: ?>

<p>Unable to log out. <a href="/">Click to return</a></p>

<?php endif; ?>
