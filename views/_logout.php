<?php

require_once "../require/require.php";
require_once "../models/User.php";

$logout = User::logout();

?>

<?php if ($logout): ?>
  <script>
    window.location.replace('/');
  </script>
<?php else: ?>
  <p>Unable to logout. Please try again.</p>
<?php endif; ?>
