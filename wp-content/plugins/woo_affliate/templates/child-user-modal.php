<?php
  foreach ( $usersDisplay as $keyUserModal => $user ) {
    $childUser = array();
    foreach ($users as $user1) {
      $checkParent =  get_user_meta($user1['ID'], 'user_parent', true);
      if ($checkParent && $checkParent == $user['ID']) {
        $user1['level'] ='Cấp 1';
        array_push($childUser, $user1);
      }
    }

    foreach ($childUser as $childUserLevel2) {
      foreach ($users as $user2) {
        $checkParent =  get_user_meta($user2['ID'], 'user_parent', true);
        if ($checkParent && $checkParent == $childUserLevel2['ID']) {
          $tempChild = $user2;
          $tempChild['isLevel2'] = 1;
          $tempChild['level'] ='Cấp 2';

          array_push($childUser, $tempChild);
        }
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
              <th>Level</th>
              <th>Tên cấp dưới</th>
              <th>Tổng hoa hồng <span class="d-none"><br /> sum (commision status 1 (<b style="color: red;">USER CON</b>))</span></th>
              <th>Tổng doanh thu <span class="d-none"><br /> sum (total_order status 1 (<b style="color: red;">USER CON</b>))</span></th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
            <?php if (count($childUser) === 0) { ?>
              <tr>
                <td>Không có cấp dưới</td>
              </tr>
            <?php } else {
              foreach ($childUser as $child) {
                if (isset($child['isLevel2'])) {
                  $childCommissions = $wpdb->get_results( 'SELECT sum(commission_level2) as childCommissions FROM ' . $tableUserCommission . ' WHERE user_id = ' . $child['ID'] . ' AND status = ' . $status['PURCHASE'] . ' ORDER BY id ASC' );
                } else {
                  $childCommissions = $wpdb->get_results( 'SELECT sum(commission) as childCommissions FROM ' . $tableUserCommission . ' WHERE user_id = ' . $child['ID'] . ' AND status = ' . $status['PURCHASE'] . ' ORDER BY id ASC' );
                }
                
                $childRevenue = $wpdb->get_results( 'SELECT sum(total_order) as childRevenue FROM ' . $tableUserCommission . ' WHERE user_id = ' . $child['ID'] . ' AND status = ' . $status['PURCHASE'] . ' ORDER BY id ASC' );
            ?>
              <tr>
                <td><?php echo $child['level'] ; ?></td>
                <td><?php echo $child['user_nicename'] . ' - ' . $child['user_login']; ?></td>
                <td><?php echo $childCommissions[0]->childCommissions; ?></td>
                <td><?php echo $childRevenue[0]->childRevenue; ?></td>
                <td>
                  <button class="button">Hiển thị cấp dưới</button>
                </td>
              </tr>
          <?php } }?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
<?php } ?>