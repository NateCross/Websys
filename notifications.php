<?php

require_once 'lib/require.php';
require_once 'lib/Product.php';
require_once 'lib/User.php';
require_once 'lib/Member.php';
require_once 'lib/Seller.php';
require_once 'lib/Notification.php';
require_once 'lib/Database.php';

$type = User::getCurrentUserType();

if ($type === 'member')
  $user = Member::getCurrentUser();
else if ($type === 'seller')
  $user = Seller::getCurrentUser();
else if ($type === 'admin')
  $user = Admin::getCurrentUser();

$user_id = User::getUserIdAttribute($user);

if ($type === 'member')
  $notifications = Notification::getNotificationsMember($user_id);
else if ($type === 'seller')
  $notifications = Notification::getNotificationsSeller($user_id);
else if ($type === 'admin')
  $notifications = Notification::getNotificationsAdmin($user_id);

Component\Header('Notifications');
?>

<h1>Notifications</h1>

<?php if ($notifications): ?>

<table>
  <tr>
    <th>Message</th>
    <th>Timestamp</th>
  </tr>
  <?php foreach($notifications as $index => $notification): ?>
    <tr>
      <td>
        <p>
          <?= $notification['message']; ?>
        </p>
      </td>
      <td>
        <p>
          <?= $notification['last_modified']; ?>
        </p>
      </td>
    </tr>
  <?php endforeach; ?>
  
</table>
<?php else: ?>

<p>You have no notifications.</p>

<?php endif; ?>