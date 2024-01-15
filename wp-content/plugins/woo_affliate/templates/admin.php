<?php
  global $wpdb;
  $successMessage = '';
  $errorMessage = '';
  $tableUser = $wpdb->prefix . 'users';
  $tableUserCommission = $wpdb->prefix . 'woo_history_user_commission';
  $status = [
    'PURCHASE' => 1,
    'USE_POINT' => 2,
    'USE_POINT_IN_PROCESS' => 4,
  ];

  $users = $wpdb->get_results( 'SELECT * FROM ' . $tableUser . ' ORDER BY id ASC', ARRAY_A );
  $userCommissions = $wpdb->get_results( 'SELECT * FROM ' . $tableUserCommission . ' ORDER BY id ASC', ARRAY_A );

  $currentPage = (! empty( $_GET['paged'] )) && ($_GET['tab'] == 'setting1') ? (int) $_GET['paged'] : 1;
  $total = count( $users );
  $perPage = 10;
  $totalPages = ceil($total/ $perPage);
  $currentPage = max($currentPage, 1);
  $currentPage = min($currentPage, $totalPages);
  $offset = ($currentPage - 1) * $perPage;
  if ($offset < 0) $offset = 0;
  $users = array_slice($users, $offset, $perPage);

  if (isset($_POST['saveSetting'])) {
    $successMessage = 'Lưu cài đặt thành công';
  }
?>

<div class="wrap">
  <h1>Hoa Hồng</h1>
  <hr />
  <?php if ($successMessage) { ?>
    <div id="message" class="success-message">
      <p><?php echo $successMessage; ?></p>
      <button id="remove-message" type="button"></button>
    </div>
  <?php } ?>
  <ul class="nav-tabs-affliate">
    <li id="tabSetting1" class="active">
      <a href="#tab-setting-1-content">Danh sách thành viên</a>
    </li>
    <li id="tabSetting2">
      <a href="#tab-setting-2-content">Cài đặt tỉ lệ hoa hồng</a>
    </li>
  </ul>
  <div class="tab-content">
    <div id="tab-setting-1-content" class="tab-pane-affliate active">
      <table class="wp-list-table widefat fixed striped table-view-list users">
        <thead>
          <tr>
            <th>Tên</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ( $users as $user ) { ?>
            <tr>
              <td><?php echo $user['display_name'] ?></td>
              <td>
                <button type="button" class="button" onclick="openLowerModal(<?php echo $user['ID']; ?>)">Hiển thị cấp dưới</button>
                <button type="button" class="button" onclick="openIncomeModal(<?php echo $user['ID']; ?>)">Thống kê thu nhập</button>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
      <ul class="pagination">
        <?php
          if ( (! empty( $_GET['paged'] )) && ($_GET['tab'] == 'setting1') ) $pg = $_GET['paged'];
          else $pg = 1;

          if ( isset( $pg ) && $pg > 1 ) {
            echo '<li><a class="button" href="'.site_url().'/wp-admin/admin.php?page=hoa-hong&paged=' . ( $pg - 1 ) . '&tab=setting1">«</a></li>';
          }

          for ( $i = 1; $i <= $totalPages; $i++ ) {
            if ( isset( $pg ) && $pg == $i )  $active = 'active';
            else $active = '';
            echo '<li><a href="'.site_url().'/wp-admin/admin.php?page=hoa-hong&paged=' . $i . '&tab=setting1" class="button ' . $active . '">' . $i . '</a></li>';
          }

          if ( isset( $pg ) && $pg < $totalPages ) {
            echo '<li><a class="button" href="'.site_url().'/wp-admin/admin.php?page=hoa-hong&paged=' . ( $pg + 1 ). '&tab=setting1">»</a></li>';
          }
        ?>
      </ul>
    </div>
    <div id="tab-setting-2-content" class="tab-pane-affliate">
      <form action="?page=hoa-hong&tab=setting2" method="POST">
        <input type="text" class="regular-text" />
        <button class="button button-primary" name="saveSetting">Lưu lại</button>
      </form>
    </div>
  </div>
  <div class="overlay d-none"></div>
  <?php foreach ( $users as $key => $user ) { 
    $childUser = $wpdb->get_results( 'SELECT * FROM ' . $tableUser . ' WHERE user_parent = ' . $user['ID'] . ' ORDER BY id ASC', ARRAY_A );
  ?>
    <div class="modal d-none modal-lower-level-<?php echo $user['ID']; ?>">
      <div class="modal-wrapper">
        <p onclick="closeLowerModal(<?php echo $user['ID']; ?>)" class="close">✕</p>
        <div class="modal-header">
          <p>Cấp dưới</p>
        </div>
        <div class="modal-content">
          <table class="wp-list-table widefat fixed striped table-view-list users">
            <thead>
              <tr>
                <th>Tên cấp dưới</th>
              </tr>
            </thead>
            <tbody>
              <?php if (count($childUser) === 0) { ?>
                <tr>
                  <td>Không có cấp dưới</td>
                </tr>
              <?php } else {
                foreach ($childUser as $child) { 
              ?>
                <tr>
                  <td><?php echo $child['display_name']; ?></td>
                </tr>
            <?php } }?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  <?php } ?>
  <?php foreach ( $users as $user ) { 
    $waitingReview = $wpdb->get_results('SELECT SUM(commission) as waitingReview FROM ' . $tableUserCommission . ' WHERE user_id = ' . $user['ID'] . ' AND status = ' . $status['USE_POINT_IN_PROCESS']);
    $actuallyReceive = $wpdb->get_results('SELECT SUM(commission) as actuallyReceive FROM ' . $tableUserCommission . ' WHERE user_id = ' . $user['ID'] . ' AND status = ' . $status['USE_POINT']);
    $totalRevenue = $wpdb->get_results('SELECT SUM(total_order) as totalRevenue FROM ' . $tableUserCommission . ' WHERE user_id = ' . $user['ID'] . ' AND status = ' . $status['PURCHASE']);
    
    $commissions = [];
    foreach ($userCommissions as $commission1) {
      if ($user['ID'] === $commission1['user_id']) {
        array_push(
          $commissions,
          array(
            'id' => $commission1['user_id'],
            'status' => $commission1['status'],
            'commission' => $commission1['commission'],
            'total_order' => $commission1['total_order']
          ));
      }
    }

    $result = array();
    foreach ($commissions as $commission2) {
      $result[$commission2['id']][] = $commission2;
    }

    $finalResult = [];
    $totalOrder = 0;
    foreach ($result as $key => $value) {
      $commission3 = 0;
      foreach ($value as $val2) {
        if ($val2['status'] == $status['PURCHASE']) {
          $commission3 += $val2['commission'];
          $totalOrder++;
        }
        if ($val2['status'] == $status['USE_POINT'] || $val2['status'] == $status['USE_POINT_IN_PROCESS']) {
          $commission3 -= $val2['commission'];
        }
      }
      
      $finalResult[$key]['commission'] = $commission3;
    }

    $childCommissions = 0;
    foreach ($users as $userChild) {
      if ($user['ID'] === $userChild['user_parent']) {
        foreach ($userCommissions as $commission4) {
          if ($commission4['user_id'] === $userChild['ID'] && $commission4['status'] == $status['PURCHASE']) {
            $childCommissions += $commission4['commission'];
          }
        }
      }
    }
  ?>
    <div class="modal d-none modal-income-<?php echo $user['ID']; ?>">
      <div class="modal-wrapper">
        <p onclick="closeIncomeModal(<?php echo $user['ID']; ?>)" class="close">✕</p>
        <div class="modal-header">
          <p>Tổng doanh thu</p>
        </div>
        <div class="modal-content">
          <div>
            <select>
              <option value="">Filter</option>
              <option value="subscriber">Theo ngày</option>
              <option value="contributor">Theo tháng</option>
              <option value="author">Theo năm</option>
            </select>
          </div>
          <br />
          <table class="wp-list-table widefat fixed striped table-view-list users">
            <thead>
              <tr>
                <th>4. Chờ đối soát (sum commission status 4)</th>
                <th>7. Thực nhận (sum commission status 2)</th>
                <th>1. Hoa hồng (sum commission status 1-2-4)</th>
                <th>5. Tổng hoa hồng (<b style="color: red;">child user:</b> sum commision status 1)</th>
                <th>6. Tổng hoa hồng đã rút (sum commission status 2)</th>
                <th>2. Tổng doanh thu (sum total_order status 1)</th>
                <th>3. Tổng đơn hàng (<b style="color: red;">child user:</b> sum row status 1)</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><?php echo $waitingReview[0]->waitingReview; ?></td>
                <td><?php echo $actuallyReceive[0]->actuallyReceive; ?></td>
                <td><?php echo $finalResult[$user['ID']]['commission']; ?></td>
                <td><?php echo $childCommissions; ?></td>
                <td><?php echo $actuallyReceive[0]->actuallyReceive; ?></td>
                <td><?php echo $totalRevenue[0]->totalRevenue; ?></td>
                <td><?php echo $totalOrder; ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  <?php } ?>
</div>
