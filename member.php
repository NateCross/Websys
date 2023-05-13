<?php

// This page shows the purchases of a member

require_once 'lib/require.php';
require_once 'lib/Member.php';

$type = Member::getCurrentUserType();
if (!$type || $type !== 'member')
  Utils\redirect('index.php');
$user = Member::getCurrentUser();

Component\Header('Purchases');

?>

<h1>Purchases</h1>

<?php var_dump(Member::getBillsWithProducts(Member::getUserIdAttribute($user))) ?>

<?php

Component\Footer();