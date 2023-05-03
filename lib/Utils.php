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