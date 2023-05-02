<?php

require_once "../require/require.php";
require_once "../models/User.php";

$logout = User::logout();

?>

<?php if ($logout): ?>

<p>Successfully logged out. <a href="/">Click to return</a></p>

<?php else: ?>

<p>Unable to log out. <a href="/">Click to return</a></p>

<?php endif; ?>
