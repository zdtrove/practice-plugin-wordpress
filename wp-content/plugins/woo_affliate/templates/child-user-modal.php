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
?>
  <div class="modal d-none modal-lower-level-<?php echo $user['ID']; ?>">
    <div class="modal-wrapper">
      <p onclick="closeLowerModal('<?php echo $user['ID']; ?>')" class="close">✕</p>
      <div class="modal-header flex-center">
        <p class="level-name">Cấp 1</p>
        <button type="button" onclick="showTopCommission('<?php echo $user['ID']; ?>')"  class="button button-primary">Top Hoa Hồng</button>
        <button type="button" onclick="showTopIntroduce('<?php echo $user['ID']; ?>')"  class="button button-primary">Top Giới thiệu</button>
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
                $childCommissions = $wpdb->get_results( 'SELECT sum(commission) as childCommissions FROM ' . $tableUserCommission . ' WHERE user_id = ' . $child['ID'] . ' AND status = ' . $status['PURCHASE'] . ' ORDER BY id ASC' );
                $childRevenue = $wpdb->get_results( 'SELECT sum(total_order) as childRevenue FROM ' . $tableUserCommission . ' WHERE user_id = ' . $child['ID'] . ' AND status = ' . $status['PURCHASE'] . ' ORDER BY id ASC' );
                
                $childUserLv2 = array();
                foreach ($users as $user2) {
                  $checkParent =  get_user_meta($user2['ID'], 'user_parent', true);
                  if ($checkParent && $checkParent == $child['ID']) {
                    $tempChild = $user2;
                    $tempChild['level'] ='Cấp 2';
          
                    array_push($childUserLv2, $tempChild);
                  }
                }
                $numItems = count($childUserLv2);
                $i = 0;
                if (count($childUserLv2) === 0) { ?>
                  <tr class="child-lv2 child-lv2-<?php echo $child['ID']; ?> d-none">
                    <td>Không có cấp dưới</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                      <button type="button" class="button" onclick="hideLevel2('<?php echo $user['ID']; ?>', '<?php echo $child['ID']; ?>')">Đóng cấp 2</button>
                    </td>
                  </tr>
                <?php } else {
                foreach ($childUserLv2 as $userLv2) { 
                  $childCommissionsLv2 = $wpdb->get_results( 'SELECT sum(commission_level2) as childCommissionsLv2 FROM ' . $tableUserCommission . ' WHERE user_id = ' . $userLv2['ID'] . ' AND status = ' . $status['PURCHASE'] . ' ORDER BY id ASC' );
                  $childRevenueLv2 = $wpdb->get_results( 'SELECT sum(total_order) as childRevenueLv2 FROM ' . $tableUserCommission . ' WHERE user_id = ' . $userLv2['ID'] . ' AND status = ' . $status['PURCHASE'] . ' ORDER BY id ASC' );
                ?>
                  <tr class="child-lv2 child-lv2-<?php echo $child['ID']; ?> d-none">
                    <td><?php echo $userLv2['level'] ; ?></td>
                    <td><?php echo $userLv2['user_nicename'] . ' - ' . $userLv2['user_login']; ?></td>
                    <td><?php echo $childCommissionsLv2[0]->childCommissionsLv2; ?></td>
                    <td><?php echo $childRevenueLv2[0]->childRevenueLv2; ?></td>
                    <td>
                      <?php
                        if(++$i === $numItems) { ?>
                          <button type="button" class="button" onclick="hideLevel2('<?php echo $user['ID']; ?>', '<?php echo $child['ID']; ?>')">Đóng cấp 2</button>
                        <?php }
                      ?>
                    </td>
                  </tr>
              <?php } }
            ?>
              <tr class="parent-lv1" data-number-lv2="<?php echo count($childUserLv2) ; ?>">
                <td><?php echo $child['level'] ; ?></td>
                <td><?php echo $child['user_nicename'] . ' - ' . $child['user_login']; ?></td>
                <td class="tdCommission"><?php echo $childCommissions[0]->childCommissions; ?></td>
                <td><?php echo $childRevenue[0]->childRevenue; ?></td>
                <td>
                  <button class="button" onclick="showLevel2('<?php echo $user['ID']; ?>', '<?php echo $child['ID']; ?>')">Hiển thị cấp dưới</button>
                </td>
              </tr>
          <?php } }?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
<?php } ?>