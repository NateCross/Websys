<?php

namespace Utils;

function generateFilename($file): string {
  return sha1_file($file['tmp_name']) 
  . "." 
  . getImageExtension($file);
}

/** * Check file MIME type
 * Determines the true type of the file     
 * $file The file uploaded from a POST request
 */
function getImageExtension($file): int | string | false {
  $fileinfo = new \finfo(FILEINFO_MIME_TYPE);

  return array_search(
    $fileinfo->file($file['tmp_name']),

    // Array below determines filetypes to be checked
    [
      'jpg' => 'image/jpeg',
      'jpeg' => 'image/jpeg',
      'png' => 'image/png',
      'gif' => 'image/gif',
    ],
    true,
  );
}

/**
 * Redirects the user to a location
 * done only through PHP
 * Note that this does not allow for automatic
 * redirection from a timer,
 * hence why this is also implemented in JavaScript
 * However, this is instant and as such is preferred
 */
function redirect(string $location) {
  header("Location: $location");
  die();
}

/**
 * Run this function before accessing the assets folder
 * PHP does not automatically create it when we move files
 */
function createAssetsFolderIfNotExists() {
  if (!is_dir('../_assets')) {
    mkdir('../_assets', 0777, true);
  }
}

/**
 * IMPORTANT: Uncomment `extension=intl` in php.ini first!
 * 
 * Formats a number to currency in PHP
 * Note that float is not ideal for storing currency
 * because of rounding errors.
 * However, due to the requirement for a 
 * simple implementation, this will suffice
 */
function formatCurrency(float $amount) {
  try {
    $formatter = new \NumberFormatter('en', \NumberFormatter::CURRENCY);
    $formatter->setAttribute($formatter::FRACTION_DIGITS, 2);
    return $formatter->formatCurrency($amount, 'PHP');
  } catch (\Exception $e) {
    return null;
  }
}