<?php

require_once "../lib/require.php";
require_once "../lib/Wishlist.php";

if (!isset($_POST['submit'])) {
  Utils\redirectPage('ERROR: Invalid form');
}
if (!Member::getCurrentUser())
  Utils\redirectPage('ERROR: Not currently logged in');

if (!Wishlist::clearWishlist())
  Utils\redirectPage('ERROR: Unable to clear wishlist.');

Utils\redirect('../wishlist.php');