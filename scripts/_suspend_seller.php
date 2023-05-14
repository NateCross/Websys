<?php

require_once "../lib/require.php";
require_once "../lib/Seller.php";
require_once "../lib/Admin.php";
require_once "../lib/Report.php";

if (Admin::getCurrentUserType() !== 'admin')
  Utils\redirect('../admin.php');

if (!isset($_POST['submit'])) {
  Redirect::handleError("Invalid form");
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
  Redirect::handleError("No seller");
}

if (!$report_id) {
  Redirect::handleError("No report");
}

if (!$days_suspended) {
  Redirect::handleError("No days suspended");
}

if (!$seller = Seller::getUserViaId($seller_id)) 
  Redirect::handleError("No seller");

if (!$report = Report::getReport($report_id))
  Redirect::handleError("No report");

if (!Seller::suspendSeller($seller_id, $days_suspended, $report_id))
  Redirect::handleError("Unable to suspend seller");

Utils\redirect('../admin-panel.php');