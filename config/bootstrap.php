<?php

require '../models/dbWorker.php';

$db = DatabaseWorker::getDb();

var_dump($db);