<?php

/**
 * Notifications are automatically added through triggers
 * They are created in config/migrate.php
 * As such, there is no need to add or delete notifications,
 * just mark as read
 */
class Notification {
  public static function getNotificationsMember(
    int $member_id,
  ) {
    try {
      $result = Database::query("
        SELECT *
        FROM `notification_member`
        WHERE `member_id` = `$member_id`;
      ");
      return $result->fetch_all(MYSQLI_ASSOC)[0];
    } catch (Exception $e) {
      return false;
    }
  }

  public static function getNotificationsSeller(
    int $seller_id,
  ) {
    try {
      $result = Database::query("
        SELECT *
        FROM `notification_seller`
        WHERE `seller_id` = `$seller_id`;
      ");
      return $result->fetch_all(MYSQLI_ASSOC)[0];
    } catch (Exception $e) {
      return false;
    }
  }

  public static function getNotificationsAdmin(
    int $admin_id,
  ) {
    try {
      $result = Database::query("
        SELECT *
        FROM `notification_admin`
        WHERE `admin_id` = `$admin_id`;
      ");
      return $result->fetch_all(MYSQLI_ASSOC)[0];
    } catch (Exception $e) {
      return false;
    }
  }

  public static function toggleNotificationReadMember(
    int $notification_id,
  ) {
    try {
      return Database::preparedQuery("
        CALL toggle_notification_read_member(?);
      ", $notification_id);
    } catch (Exception $e) {
      return false;
    }
  }

  public static function toggleNotificationReadSeller(
    int $notification_id,
  ) {
    try {
      return Database::preparedQuery("
        CALL toggle_notification_read_seller(?);
      ", $notification_id);
    } catch (Exception $e) {
      return false;
    }
  }

  public static function toggleNotificationReadAdmin(
    int $notification_id,
  ) {
    try {
      return Database::preparedQuery("
        CALL toggle_notification_read_admin(?);
      ", $notification_id);
    } catch (Exception $e) {
      return false;
    }
  }
}