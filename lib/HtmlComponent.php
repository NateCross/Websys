<?php

namespace Component;

require_once 'User.php';
require_once 'Member.php';
require_once 'Seller.php';
require_once 'Admin.php';
require_once 'Session.php';

/**
 * Header component to be executed at the start of every page
 * Execute this after any potential redirects and error
 * checks, or else it will break that functionality
 */
function Header(string $title) { ?>
  <?php $type = \User::getCurrentUserType(); ?>
  <?php 
    if ($type === 'member')
      $user = \Member::getCurrentUser();
    else if ($type === 'seller')
      $user = \Seller::getCurrentUser();
    else if ($type === 'admin')
      $user = \Admin::getCurrentUser();
    else {
      $user = null;
    }

    if (!$user) {
      \Session::delete('user');
      \Session::delete('type');
    }
  ?>

  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <header>
      <div class="store-logo">
        <a href="index.php">Turn Zero Online</a>
      </div>
      <div class="header-categories-container">
        <a href="category.php?id=1">Board Games</a>
        <a href="category.php?id=2">Gaming Accessories</a>
        <a href="category.php?id=3">Technology</a>
      </div>
      <div class="header-account-container">
        <?php if ($user): ?>
          <form 
            action="scripts/_logout.php"
            method="POST"
          >
            <input type="submit" name="submit" value="Logout">
          </form>
          <a href="account.php">Account</a>
        <?php else: ?>
          <a href="login.php">Login</a>
          <a> | </a>
          <a href="register.php">Register</a>
        <?php endif; ?>
      </div>
      <div class="header-actions-container">
        <?php if (\User::getCurrentUserType() === 'seller'): ?>
          <a href="seller.php?id=<?= \User::getUserIdAttribute($user) ?>">My Store</a>
          <a href="product_add.php">Add Product For Sale</a>
        <?php elseif (\User::getCurrentUserType() === 'member'): ?>
          <a href="cart.php">Cart</a>
          <a href="purchases.php">Purchases</a>
        <?php elseif (\User::getCurrentUserType() === 'admin'): ?>
          <a href="admin-panel.php">Admin Panel</a>
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
    <main>
<?php } ?>

<?php 

/**
 * Footer component to be placed at the bottom of every page
 * Primary purpose is to properly close the HTML
 */
function Footer() { 

?>
</main>
<footer>
  <div class="content-wrapper">
    <p> 
      &copy; <script type="text/javascript">document.write( new Date().getFullYear() );</script>
  Turn Zero Online
    </p>
    <p class="admin-login-link">
      <a href="admin.php">Admin Login</a>
    </p>
  </div>
</footer>
</body>
</html>

<?php } ?>