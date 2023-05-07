<?php

require_once 'Database.php';

class Report {
  public static function getReports() {
    try {
      $result = Database::query("
        SELECT * FROM report_with_users;
      ");
      return $result->fetch_all(MYSQLI_ASSOC);
    } catch (Exception $e) {
      return false;
    }
  }

  public static function getReport(int $report_id) {
    try {
      return Database::query("
        SELECT * FROM report_with_users
        WHERE id = $report_id;
      ")->fetch_all(MYSQLI_ASSOC);
    } catch (Exception $e) {
      return false;
    }
  }

  public static function toggleReportStatus(
    int $report_id
  ) {
    try {
      return Database::preparedQuery("
        CALL toggle_report_status(?);
      ", $report_id);
    } catch (Exception $e) {
      return false;
    }
  }

  public static function getMessage($report) {
    return $report['message'];
  }

  /**
   * Use this query with the report_with_users view
   */
  public static function getSellerName($report) {
    return $report['seller_name'];
  }

  /**
   * Use this query with the report_with_users view
   */
  public static function getMemberName($report) {
    return $report['member_name'];
  }

  public static function getSellerId($report) {
    return $report['seller_id'];
  }

  public static function getMemberId($report) {
    return $report['member_id'];
  }

  public static function getStatus($report) {
    return $report['status'];
  }

  public static function getId($report) {
    return $report['id'];
  }

  public static function getLastModified($report) {
    return $report['last_modified'];
  }

  public static function getSellerSuspendedUntil($report) {
    return $report['seller_suspended_until'];
  }
}