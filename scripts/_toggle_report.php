<?php

require_once "../lib/require.php";
require_once "../lib/Report.php";
require_once "../lib/Admin.php";

if (Admin::getCurrentUserType() !== 'admin')
  Utils\redirect('../admin.php');

if (!isset($_POST['submit'])) {
  Utils\redirectPage("ERROR: Invalid form");
}

$report_id = filter_input(
  INPUT_POST, 
  'report_id', 
  FILTER_VALIDATE_INT,
);

if (!$report_id) {
  Utils\redirectPage("ERROR: No report");
}

if (!$report = Report::getReport($report_id)) 
  Utils\redirectPage("ERROR: Report does not exist");

if (!Report::toggleReportStatus($report_id))
  Utils\redirectPage("ERROR: Unable to toggle report");

Utils\redirect('../admin-panel.php');