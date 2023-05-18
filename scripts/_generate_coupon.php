<?php

require_once "../lib/require.php";
require_once "../lib/Coupon.php";

if(ISSET($_POST['saveCoupon'])){
	$coupon_code = Coupon::coupon(10);
	$discount = $_POST['discount'];
	$status = "Valid";		

	Coupon::saveCoupon($coupon_code, $discount, $status);

	Utils\redirectPage("Your generated coupon is: $coupon_code", '../admin-panel.php', 5000);
}