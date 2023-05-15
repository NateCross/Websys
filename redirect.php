<?php

// Handles redirection with a message
// This is handled through `Utils\redirectMessage()`
// in `lib/Utils.php`
// Regular redirects that happen instantly are
// handled with `Utils\redirect()`

require_once 'lib/require.php';

$message = Session::get('redirect_message');
$location = Session::get('redirect_location');
$duration = Session::get('redirect_duration');

if (!$message || !$location || !$duration) header('Location: /');


// We use the session variables as a way of passing
// parameters to another page. So we unset them here
// since they are no longer necessary
Session::delete('redirect_message');
Session::delete('redirect_location');
Session::delete('redirect_duration');

Component\Header('Redirecting...');

?>

<div class="redirect-container">
  <p><?= $message; ?></p>
  <p>Redirecting...</p>
</div>

<script type="module">
  import { redirect } from './js/utils.js';
  redirect('<?= $location ?>', <?= $duration ?>);
</script>

<?php Component\Footer(); ?>

<?php die(); ?>