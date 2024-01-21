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
                $childCommissions = $wpdb->get_results( 'SELECT sum(commission) as childCommissions FROM ' . $tableUserCommission . ' WHERE user_id = ' . $child['ID'] . ' AND status = ' . $status['PURCHASE'] . ' ORDER BY id ASC' );
                $childRevenue = $wpdb->get_results( 'SELECT sum(total_order) as childRevenue FROM ' . $tableUserCommission . ' WHERE user_id = ' . $child['ID'] . ' AND status = ' . $status['PURCHASE'] . ' ORDER BY id ASC' );
                
                $arrayLevel2 = [];
                $childCommissionsLevel2Total = 0;
                $childRevenueLevel2Total = 0;
                foreach ($users as $userLevel2) {
                  $checkParentLevel2 =  get_user_meta($userLevel2['ID'], 'user_parent', true);
                  if ($checkParentLevel2 && $checkParentLevel2 == $child['ID']) {
                    $childCommissionsLevel2 = $wpdb->get_results( 'SELECT sum(commission) as childCommissionsLevel2 FROM ' . $tableUserCommission . ' WHERE user_id = ' . $userLevel2['ID'] . ' AND status = ' . $status['PURCHASE'] . ' ORDER BY id ASC' );
                    $childRevenueLevel2 = $wpdb->get_results( 'SELECT sum(total_order) as childRevenueLevel2 FROM ' . $tableUserCommission . ' WHERE user_id = ' . $userLevel2['ID'] . ' AND status = ' . $status['PURCHASE'] . ' ORDER BY id ASC' );
                    $childCommissionsLevel2Total += $childCommissionsLevel2[0]->childCommissionsLevel2;
                    $childRevenueLevel2Total += $childRevenueLevel2[0]->childRevenueLevel2;
                  }
                }
            ?>
              <tr>
                <td><?php echo $child['user_nicename'] . ' - ' . $child['user_login']; ?></td>
                <td><?php echo $childCommissions[0]->childCommissions + $childCommissionsLevel2Total; ?></td>
                <td><?php echo $childRevenue[0]->childRevenue + $childRevenueLevel2Total; ?></td>
              </tr>
          <?php } }?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
<?php } ?>