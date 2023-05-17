<?php

require_once '../lib/require.php';
require_once '../lib/Database.php';
require_once '../lib/User.php';
require_once '../lib/Member.php';
require_once '../lib/Product.php';


if (!isset($_POST['submit']))
  Utils\redirectPage("ERROR: Invalid form");
  
  $type = User::getCurrentUserType();
if (!$type || $type !== 'member')
  Utils\redirectPage("ERROR: Not a member");

$member_id = Session::get('user');
$prod_id = $_POST['prod_id'];

$sql = "INSERT INTO product_wishlist (member_id, product_id) VALUES ($member_id, '$prod_id')";

Database::query($sql);

Utils\redirectPage("Successfully added to wishlist");
?>
