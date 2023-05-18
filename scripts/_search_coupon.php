<?php

require_once "../lib/require.php";
require_once "../lib/Coupon.php";
require_once "../lib/Cart.php";

if(ISSET($_POST['search_coupon'])){
	$coupon_code = $_POST['coupon_code'];
	$result = Cart::searchCoupon($coupon_code);

	if (!Cart::setCoupon($result))
		Utils\redirectPage('ERROR: Coupon not found', 'cart.php');
	Utils\redirect('../cart.php');
}