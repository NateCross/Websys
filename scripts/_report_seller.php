<?php

require_once '../lib/require.php';
require_once '../lib/Member.php';
require_once '../lib/Seller.php';

if (!isset($_POST['submit'])) {
  ErrorHandler::handleError("Invalid form. Please try again.");
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
  ErrorHandler::handleError("No member");
else if (!Seller::getUserViaId($seller_id))
  ErrorHandler::handleError("No seller");
else if (!$message)
  ErrorHandler::handleError("No message");

?>

<?php if (Member::reportSeller($member_id, $seller_id, $message)): ?>
  <p>Successfully reported seller.</p>
  <a href="../index.php">Click to return</a>
<?php else: ?>
  <p>An error has occurred</p>
  <a href="../index.php">Click to return</a>
<?php endif; ?>