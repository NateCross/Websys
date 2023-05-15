<?php

require_once '../lib/require.php';
require_once '../lib/Member.php';
require_once '../lib/Seller.php';

if (!isset($_POST['submit'])) {
  Utils\redirectPage("ERROR: Invalid form");
}

[
  'message' => $message,
  'member_id' => $member_id,
  'seller_id' => $seller_id,
] = filter_input_array(INPUT_POST, [
  'message' => FILTER_SANITIZE_SPECIAL_CHARS,
  'member_id' => FILTER_VALIDATE_INT,
  'seller_id' => FILTER_VALIDATE_INT,
]);

if (!Member::getUserViaId($member_id))
  Utils\redirectPage("ERROR: No member");
else if (!Seller::getUserViaId($seller_id))
  Utils\redirectPage("ERROR: No seller");
else if (!$message)
  Utils\redirectPage("ERROR: No message");

?>

<?php if (Member::reportSeller($member_id, $seller_id, $message)): ?>
  <p>Successfully reported seller.</p>
  <a href="../index.php">Click to return</a>
<?php else: ?>
  <p>An error has occurred</p>
  <a href="../index.php">Click to return</a>
<?php endif; ?>