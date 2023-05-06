<?php

require_once "../lib/require.php";
require_once "../lib/Report.php";
require_once "../lib/Admin.php";

if (Admin::getCurrentUserType() !== 'admin')
  Utils\redirect('../admin.php');

if (!isset($_POST['submit'])) {
  ErrorHandler::handleError("Invalid form");
}

$report_id = filter_input(
  INPUT_POST, 
  'report_id', 
  FILTER_VALIDATE_INT,
);

if (!$report_id) {
  ErrorHandler::handleError("No report");
}

if (!$report = Report::getReport($report_id)) 
  ErrorHandler::handleError("No report");

if (!Report::toggleReportStatus($report_id))
  ErrorHandler::handleError("Unable to toggle report");

Utils\redirect('../admin-panel.php');