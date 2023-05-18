<?php
require_once "require.php";
require_once "Database.php";



class Coupon{
	public static function saveCoupon(
		string $coupon_code,
		int $discount,
		string $status,
	  ): bool {
		try {
		  return Database::preparedQuery("
			INSERT INTO `coupon` (
			  `coupon_code`,
			  `discount`,
			  `status`
			) VALUES (?, ?, ?);
		  ", $coupon_code, $discount, $status);
		} catch (Exception $e) {
		  return false;
		}
	  }

	  public static function coupon($l){
		$coupon = "TZ".substr(str_shuffle(str_repeat('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ',$l-2)),0,$l-2);

		return $coupon;
	}
}

?>