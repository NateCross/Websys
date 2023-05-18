<?php

require_once "../lib/require.php";
require_once "../lib/Member.php";
require_once "../lib/Wishlist.php";

if (!isset($_POST['submit'])) {
  Utils\redirectPage('ERROR: Invalid form');
}
if (!Member::getCurrentUser())
  Utils\redirectPage('ERROR: Not currently logged in');

[
  'index' => $index,
] = filter_input_array(INPUT_POST, [
  'index' => FILTER_VALIDATE_INT,
]);

if (!Wishlist::deleteFromWishlist($index))
  Utils\redirectPage('ERROR: Unable to delete product from wishlist.');

Utils\redirect('../wishlist.php');