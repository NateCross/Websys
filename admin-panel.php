<?php

// This is where an admin can
// - see reports, both read and unread
// - create other admin accounts (similar to register)

use function Utils\redirect;

require_once 'lib/require.php';
require_once 'lib/Admin.php';
require_once 'lib/Report.php';

if (Admin::getCurrentUserType() !== 'admin')
  redirect('admin.php');

Component\Header("Admin Panel");

?>

<h1>Admin Panel</h1>

<div class="reports-container">
  <h2 class="reports-header">Reports</h2>

  <?php if ($reports = Report::getReports()): ?>
    <div class="toggle-closed-reports-container">
      <label for="toggle-closed-reports">Show Closed Reports</label>
      <input type="checkbox" id="toggle-closed-reports">
    </div>

    <table id="reports-table">
      <tr>
        <th>Status</th>
        <th>Seller</th>
        <th>Seller Suspended Until</th>
        <th>Member</th>
        <th>Message</th>
        <th>Last Modified</th>
        <th>Actions</th>
      </tr>
      <?php foreach ($reports as $report): ?>
        <tr>
          <td><?= Report::getStatus($report) ?></td>
          <td>
            <a 
              href="seller.php?id=<?= Report::getSellerId($report) ?>"
            >
              <?= Report::getSellerName($report) ?>
            </a>
          </td>
          <td>
            <?= Report::getSellerSuspendedUntil($report) ?>
          </td>
          <td>
            <a 
              href="member.php?id=<?= Report::getMemberId($report) ?>"
            >
              <?= Report::getMemberName($report) ?>
            </a>
          </td>
          <td><?= Report::getMessage($report) ?></td>
          <td><?= Report::getLastModified($report) ?></td>
          <td>
            <div class="admin-panel-button-container">
              <form 
                action="scripts/_toggle_report.php"
                method="POST"
              >
                <input type="hidden" name="report_id" value="<?= Report::getId($report) ?>">

                <input 
                  type="submit" 
                  name="submit" 
                  value="<?= Report::getStatus($report) === 'open' ? 'Close Report' : 'Open Report' ?>">
              </form>
              <button
                class="toggle-suspend-dialog"
                value="<?= Report::getId($report) ?> <?= Report::getSellerId($report) ?>"
              >
                Suspend User
              </button>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>

    <dialog id="suspendDialog">
      <form 
        action="scripts/_suspend_seller.php"
        method="POST"
      >
        <input 
          type="hidden" 
          name="seller_id" 
          id="seller_id"
        >
        <input 
          type="hidden" 
          name="report_id" 
          id="report_id"
        >
        <div class="form-input-container">
          <label for="days_suspended">Days Suspended</label>
          <input type="number" name="days_suspended" id="days_suspended" min="0">
        </div>
        <input type="submit" name="submit" value="Submit">
        <button value="cancel" formmethod="dialog">Cancel</button>
      </form>
    </dialog>
  <?php else: ?>
    <p>No reports.</p>
  <?php endif; ?>
</div>

<div class="admin-account-create-container">
  <h2 class="admin-account-create-header">Create Admin Account</h2>
  
  <form action="scripts/_register.php" method="POST">
    <input type="hidden" name="type" value="Admin">

    <div class="form-input-container">
      <label for="Email">Email</label>
      <input type="email" name="email" id="email" required>
    </div>

    <div class="form-input-container">
      <label for="username">Username</label>
      <input type="text" name="username" id="username" required>
    </div>

    <div class="form-input-container">
      <label for="password">Password</label>
      <input type="password" name="password" id="password" required>
    </div>

    <div class="form-input-container">
      <label for="confirm_password">Confirm Password</label>
      <input type="password" name="confirm_password" id="confirm_password" required>
    </div>

    <div class="form-input-container">
      <label for="address">Address</label>
      <input type="text" name="address" id="address" required>
    </div>

    <div class="form-input-container">
      <label for="contact_number">Contact Number</label>
      <input type="text" name="contact_number" id="contact_number" required>
    </div>

    <div class="form-input-container">
      <input type="submit" name="submit" value="Submit">
    </div>
  </form>
</div>

<script src="js/admin-panel.js"></script>

<?php Component\Footer(); ?>