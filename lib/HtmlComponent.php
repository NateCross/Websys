<?php

namespace Component;

require_once 'User.php';

/**
 * Header component to be executed at the start of every page
 * Execute this after any potential redirects and error
 * checks, or else it will break that functionality
 */
function Header(string $title) { ?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
  </head>
  <body>
    <header>
      <div class="store-logo">
        <a href="index.php">Insert Store Name Here</a>
      </div>
      <div class="header-account-container">
        <?php if ($user = \User::getCurrentUser()): ?>
          <form 
            action="scripts/_logout.php"
            method="POST"
          >
            <input type="submit" name="submit" value="Logout">
          </form>
          <!-- <button id="logout">Logout</button> -->
          <a href="account.php">Account</a>
        <?php else: ?>
          <a href="login.php">Login</a>
          <a href="register.php">Register</a>
        <?php endif; ?>
      </div>
      <div class="header-actions-container">
        <?php if (\User::getCurrentUserType() === 'seller'): ?>
          <a href="seller.php?id=<?= \User::getUserIdAttribute($user) ?>">My Store</a>
          <a href="product_add.php">Add Product For Sale</a>
        <?php elseif (\User::getCurrentUserType() === 'member'): ?>
          <a href="cart.php">Cart</a>
        <?php endif; ?>
      </div>
      <div class="header-search-container">
        <form action="search.php" method="GET">
          <input type="text" name="search" id="search">
          <select name="type" id="type">
            <option value="product">Product</option>
            <option value="seller">Seller</option>
          </select>

          <input type="submit" value="Search">
        </form>
      </div>
    </header>
<?php } ?>

<?php
/**
 * Footer component to be placed at the bottom of every page
 * Primary purpose is to properly close the HTML
 */
?>
<?php function Footer() { ?>

</body>
</html>

<?php } ?>