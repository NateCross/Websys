<?php

require_once 'lib/require.php';
require_once 'lib/Category.php';

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_ENCODED);

if (!$id)
  Utils\redirect('index.php');

$category = Category::getCategories($id);
$products = Category::getProducts($id);

Component\Header(Category::getName($category));

var_dump($products);

?>