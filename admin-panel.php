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

?>

<h1>Admin Panel</h1>

<div class="reports-container">
  <h2 class="reports-header">Reports</h2>

  <div class="toggle-closed-reports-container">
    <label for="toggle-closed-reports">Show Closed Reports</label>
    <input type="checkbox" id="toggle-closed-reports">
  </div>

  <?php if ($reports = Report::getReports()): ?>
    <table id="reports-table">
      <tr>
        <th>Status</th>
        <th>Seller</th>
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
            <a 
              href="member.php?id=<?= Report::getMemberId($report) ?>"
            >
              <?= Report::getMemberName($report) ?>
            </a>
          </td>
          <td><?= Report::getMessage($report) ?></td>
          <td><?= Report::getLastModified($report) ?></td>
          <td>
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
              value="<?= Report::getSellerId($report) ?>"
            >
              Suspend User
            </button>
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
        <label for="days_suspended">Days Suspended</label>
        <input type="number" name="days_suspended" id="days_suspended" min="0">
        <input type="submit" name="submit" value="Submit">
      </form>
    </dialog>
  <?php endif; ?>
</div>

<script src="js/admin-panel.js"></script>