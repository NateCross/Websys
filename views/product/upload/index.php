<?php

require_once "../../../require/require.php";
require_once "../../../models/User.php";

$user = User::getCurrentUser();
$type = User::getCurrentUserType();

?>

<?php if (!$user): ?>
<p>No user. Please try again.</p>
<script type="module">
  import { redirect } from '../../utils.js';
  redirect('/', 1000);
</script>
<?php endif; ?>

<?php if (!$type === 'seller'): ?>
<p>Not a seller. Please try again.</p>
<script type="module">
  import { redirect } from '../../utils.js';
  redirect('/', 1000);
</script>
<?php endif; ?>

<h1>Upload File</h1>