<?php

// Require this file at the top of every page in views/

require_once dirname(__dir__) . '/models/Database.php';

$db = Database::getDb();

session_start();