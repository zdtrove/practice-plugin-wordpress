<?php
  foreach ( $usersDisplay as $keyUserModal => $user ) {
    $childUser = array();
    foreach ($users as $user1) {
      $checkParent =  get_user_meta($user1['ID'], 'user_parent', true);
      if ($checkParent && $checkParent == $user['ID']) {
        array_push($childUser, $user1);
      }
    }
?>
  <div class="modal d-none modal-lower-level-<?php echo $keyUserModal; ?>">
    <div class="modal-wrapper">
      <p onclick="closeLowerModal(<?php echo $keyUserModal; ?>)" class="close">✕</p>
      <div class="modal-header">
        <p>Cấp dưới</p>
      </div>
      <div class="modal-content">
        <table class="wp-list-table widefat fixed striped table-view-list users">
          <thead>
            <tr>
              <th>Tên cấp dưới</th>
              <th>Tổng hoa hồng <span class="d-none"><br /> sum (commision status 1 (<b style="color: red;">USER CON</b>))</span></th>
              <th>Tổng doanh thu <span class="d-none"><br /> sum (total_order status 1 (<b style="color: red;">USER CON</b>))</span></th>
            </tr>
          </thead>
          <tbody>
            <?php if (count($childUser) === 0) { ?>
              <tr>
                <td>Không có cấp dưới</td>
              </tr>
            <?php } else {
              foreach ($childUser as $child) {
                $childUser2 = [];
                foreach ($users as $user2) {
                  $checkParent2 =  get_user_meta($user2['ID'], 'user_parent', true);
                  if ($checkParent2 && $checkParent2 == $child['ID']) {
                    array_push($childUser2, $user2);
                  }
                }
                $childCommissions = 0;
                $totalRevenue = 0;
                foreach ($childUser2 as $child2) {
                  foreach ($userCommissions as $commission5) {
                    if ($commission5['user_id'] === $child2['ID'] && $commission5['status'] == $status['PURCHASE']) {
                      $childCommissions += $commission5['commission'];
                      $totalRevenue += $commission5['total_order'];
                    }
                  }
                }
            ?>
              <tr>
                <td><?php echo $child['user_login']; ?></td>
                <td><?php echo $childCommissions; ?></td>
                <td><?php echo $totalRevenue; ?></td>
              </tr>
          <?php } }?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
<?php } ?>