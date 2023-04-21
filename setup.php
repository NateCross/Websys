<?php

function setupEnv(): bool {
  if (file_exists('.env')) return false;

  return copy('.env.example', '.env');
}

setupEnv();

echo "Fill up the fields in .env, then run migrate.php";