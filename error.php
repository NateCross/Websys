<?php

require_once 'lib/require.php';

$error = Session::get('error');
$duration = Session::get('error_duration');

?>

<?php
  if (!$error || $duration) header('Location: /');
?>

<?php

// We use the session variables as a way of passing
// parameters to another page. So we unset them here
// since they are no longer necessary
Session::delete('error');
Session::delete('error_duration');

Component\Header('Error');

?>

<div class="error-container">
  <p>Error: <?= $error; ?></p>
  <p>Redirecting to home page...</p>
  <script type="module">
    import { redirect } from './js/utils.js';
    redirect('/', <?= $duration ?>);
  </script>
</div>

<?php Component\Footer(); ?>

<?php die(); ?>