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

<!-- Dane's part -->
<style>
body {font-family: Arial, Helvetica, sans-serif;}
* {box-sizing: border-box;}

/* Button used to open the contact form - fixed at the bottom of the page */
.open-button {
  background-color: #555;
  color: white;
  padding: 16px 20px;
  border: none;
  cursor: pointer;
  opacity: 0.8;
  position: fixed;
  bottom: 23px;
  right: 28px;
  width: 280px;
}

/* The popup form - hidden by default */
.form-popup {
  display: none;
  position: fixed;
  bottom: 0;
  right: 15px;
  border: 3px solid #f1f1f1;
  z-index: 9;
}

/* Add styles to the form container */
.form-container {
  max-width: 300px;
  padding: 10px;
  background-color: white;
}

/* Full-width input fields */
.form-container input[type=text], .form-container input[type=password] {
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  border: none;
  background: #f1f1f1;
}

/* When the inputs get focus, do something */
.form-container input[type=text]:focus, .form-container input[type=password]:focus {
  background-color: #ddd;
  outline: none;
}

/* Set a style for the submit/login button */
.form-container .btn {
  background-color: #04AA6D;
  color: white;
  padding: 16px 20px;
  border: none;
  cursor: pointer;
  width: 100%;
  margin-bottom:10px;
  opacity: 0.8;
}

/* Add a red background color to the cancel button */
.form-container .cancel {
  background-color: red;
}

/* Add some hover effects to buttons */
.form-container .btn:hover, .open-button:hover {
  opacity: 1;
}
</style>

<button class="open-button" onclick="openForm()">Generate Coupon</button>

<div class="form-popup" id="myForm">
  <form action="scripts/_generate_coupon.php" class="form-container" method="POST">
   
    <div>
    <label for="psw"><b>Percentage to be Discounted:</b></label>
    <input type="number" class="form-control" name="discount" min="10" required="required"/>
    </div>
    <div>
    <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
    <button name="saveCoupon" class="btn btn-primary">Save</button>
    </div>
  </form>
</div>

<script>
function openForm() {
  document.getElementById("myForm").style.display = "block";
}

function closeForm() {
  document.getElementById("myForm").style.display = "none";
}

</script>

<script src="js/admin-panel.js"></script>

<?php Component\Footer(); ?>