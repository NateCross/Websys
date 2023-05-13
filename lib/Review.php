<?php

require_once 'Database.php';

class Review {
  /**
   * Add a review for a product
   * Because of triggers, automatically notifies the seller
   * that a review has been posted
   * @param $member_id ID of the member
   * @param $product_id ID of the product
   * @param $rating Rating from 1 to 5
   * @param $comment Comment displayed alongside the review
   * @return bool True if query was successful, false otherwise
   */
  public static function addReview(
    int $member_id,
    int $product_id,
    int $rating,
    string $comment,
  ): bool {
    try {
      return Database::preparedQuery("
        INSERT INTO review (
          member_id,
          product_id,
          rating,
          comment
        ) VALUES (?, ?, ?, ?);
      ", $member_id, $product_id, $rating, $comment);
    } catch (Exception $e) {
      return false;
    }
  }

  /**
   * Get all the reviews for a certain product
   * @param $product_id The ID of the product
   * @return array An array of all the reviews
   */
  public static function getReviews(
    int $product_id,
  ): array | null {
    try {
      $result = Database::query("
        SELECT 
          review.*,
          member.name member_name
        FROM review
          LEFT JOIN member
            ON member.id = review.member_id
        WHERE product_id = $product_id
      ");
      return $result->fetch_all(MYSQLI_ASSOC);
    } catch (Exception $e) {
      return null;
    }
  }

  /**
   * Get the average rating from all reviews of a product
   * This leverages a function in the database to easily
   * return it
   * @param int $product_id The ID of the product
   * @return float The average rating of all reviews for a product
   */
  public static function getAverageRating(
    int $product_id,
  ) {
    try {
      $result = Database::query("
        SELECT get_review_rating_average($product_id);
      ");

      // Additional preprocessing is needed because
      // the value returned is a string
      $number = $result->fetch_all(MYSQLI_NUM)[0][0];

      return (float) $number;
    } catch (Exception $e) {
      return false;
    }
  }

  public static function getComment($review) {
    return $review['comment'];
  }

  public static function getRating($review) {
    return $review['rating'];
  }

  public static function getMemberName($review) {
    return $review['member_name'];
  }

  public static function getMemberId($review) {
    return $review['member_id'];
  }

  public static function getTimestamp($review) {
    return $review['last_modified'];
  }
}