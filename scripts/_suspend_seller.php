<?php

require_once "../lib/require.php";
require_once "../lib/Seller.php";
require_once "../lib/Admin.php";
require_once "../lib/Report.php";

if (Admin::getCurrentUserType() !== 'admin')
  Utils\redirect('../admin.php');

if (!isset($_POST['submit'])) {
  Utils\redirectPage("ERROR: Invalid form");
}

[
  'seller_id' => $seller_id,
  'report_id' => $report_id,
  'days_suspended' => $days_suspended,
] = filter_input_array(INPUT_POST, [
  'seller_id' => FILTER_VALIDATE_INT, 
  'report_id' => FILTER_VALIDATE_INT, 
  'days_suspended' => FILTER_VALIDATE_INT,
]);

if (!$seller_id) {
  Utils\redirectPage("ERROR: Seller does not exist");
}

if (!$report_id) {
  Utils\redirectPage("ERROR: Report does not exist");
}

if (!$days_suspended) {
  Utils\redirectPage("ERROR: No days suspended");
}

if (!$seller = Seller::getUserViaId($seller_id)) 
  Utils\redirectPage("ERROR: Seller does not exist");

if (!$report = Report::getReport($report_id))
  Utils\redirectPage("ERROR: Report does not exist");

if (!Seller::suspendSeller($seller_id, $days_suspended, $report_id))
  Utils\redirectPage("ERROR: Unable to suspend seller");

Utils\redirect('../admin-panel.php');