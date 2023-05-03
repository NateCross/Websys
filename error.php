<?php

require_once 'lib/require.php';

$error = Session::get('error');

?>

<?php if (!$error): ?>
  <script type="module">
    import { redirect } from 'lib/utils.js';
    redirect('/');
  </script>
<?php die(); endif; ?>

<?php

Session::delete('error');

?>

<div class="error-container">
  <p>Error: <?= $error; ?></p>
  <p>Redirecting to home page...</p>
  <script type="module">
    import { redirect } from 'lib/utils.js';
    redirect('/', 3000);
  </script>
</div>

<?php die(); ?>