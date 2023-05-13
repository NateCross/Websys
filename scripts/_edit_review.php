<?php

require_once "../lib/require.php";
require_once "../lib/Member.php";
require_once "../lib/Review.php";

if (!isset($_POST['submit'])) {
  ErrorHandler::handleError("Invalid form");
}
if (!Member::getCurrentUser())
  ErrorHandler::handleError("Not currently logged in");

[
  'review_id' => $review_id,
  'rating' => $rating,
  'comment' => $comment,
] = filter_input_array(INPUT_POST, [
  'review_id' => FILTER_VALIDATE_INT,
  'rating' => FILTER_VALIDATE_INT,
  'comment' => FILTER_SANITIZE_SPECIAL_CHARS,
]);

if (!Review::updateReview(
  $review_id,
  $rating,
  $comment,
))
  ErrorHandler::handleError('Unable to update review');

Utils\redirect("../purchases.php");