<?php

function setupEnv(): bool {
  if (file_exists('../.env')) return false;

  return copy('../.env.example', '../.env');
}

if (!setupEnv()) {
  echo ".env file exists";
  die();
}

echo "Fill up the fields in .env, then run migrate.php";