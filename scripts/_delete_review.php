<?php

require_once "../lib/require.php";
require_once "../lib/Member.php";
require_once "../lib/Review.php";

if (!isset($_POST['submit'])) {
  Redirect::handleError("Invalid form");
}
if (!Member::getCurrentUser())
  Redirect::handleError("Not currently logged in");

[
  'review_id_delete' => $review_id,
] = filter_input_array(INPUT_POST, [
  'review_id_delete' => FILTER_VALIDATE_INT,
]);

if (!Review::deleteReview(
  $review_id,
))
  Redirect::handleError('Unable to delete review');

Utils\redirect("../purchases.php");