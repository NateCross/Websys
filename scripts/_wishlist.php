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
$prod_id = (int) $_POST['prod_id'];

if (Database::query("
  SELECT *
  FROM product_wishlist
  WHERE 
    member_id = $member_id
  AND
    product_id = $prod_id;
")->num_rows)
  Utils\redirect('../wishlist.php');

$sql = "INSERT INTO product_wishlist (member_id, product_id) VALUES ($member_id, '$prod_id')";

Database::query($sql);

Utils\redirectPage("Successfully added to wishlist", 'wishlist.php');
?>
