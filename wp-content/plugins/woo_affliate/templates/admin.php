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

  $usersDisplay = [];
  $users = $wpdb->get_results( 'SELECT * FROM ' . $tableUser . ' ORDER BY id ASC', ARRAY_A );
  $userCommissions = $wpdb->get_results( 'SELECT * FROM ' . $tableUserCommission . ' ORDER BY id ASC', ARRAY_A );

  if (isset($_GET['searchUser'])) {
    $usersSearch = $wpdb->get_results( 'SELECT * FROM ' . $tableUser . ' WHERE user_login LIKE "%' . $_GET['username'] . '%" ORDER BY id ASC', ARRAY_A );
    $usersDisplay = $usersSearch;
  } else {
    $usersDisplay = $users;
  }

  $currentPage = (! empty( $_GET['paged'] )) && ($_GET['tab'] == 'setting1') ? (int) $_GET['paged'] : 1;
  $total = count( $usersDisplay );
  $perPage = 10;
  $totalPages = ceil($total/ $perPage);
  $currentPage = max($currentPage, 1);
  $currentPage = min($currentPage, $totalPages);
  $offset = ($currentPage - 1) * $perPage;
  if ($offset < 0) $offset = 0;
  $usersDisplay = array_slice($usersDisplay, $offset, $perPage);

  if (isset($_POST['saveSetting'])) {
    $wpAffliate = get_option('wp_affliate');
    if ($wpAffliate) {
      update_option('wp_affliate',$_POST['wp_affliate']);
    } else {
      add_option('wp_affliate',$_POST['wp_affliate']);
    }
    $successMessage = 'Lưu cài đặt thành công';
  }

  if (isset($_POST['updateStatus'])) {
    $update = $wpdb->update($tableUserCommission, ['status' => $_POST['status']], ['id' => $_POST['userCommissionId']]);
    $successMessage = 'Cập nhật trạng thái thành công';
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
    <li id="tabSetting1" class="active" onclick="changeUrl(1)">
      <a href="#tab-setting-1-content">Danh sách thành viên</a>
    </li>
    <li id="tabSetting2" onclick="changeUrl(2)">
      <a href="#tab-setting-2-content">Cài đặt tỉ lệ hoa hồng</a>
    </li>
    <li id="tabSetting3" onclick="changeUrl(3)">
      <a href="#tab-setting-3-content">Danh sách chuyển tiền</a>
    </li>
    <li id="tabSetting4" onclick="changeUrl(4)">
      <a href="#tab-setting-4-content">Thống kê doanh thu</a>
    </li>
  </ul>
  <div class="tab-content">
    <div id="tab-setting-1-content" class="tab-pane-affliate active" style="overflow: auto;">
      <br />
      <form action="?page=hoa-hong&paged=1&tab=setting1" method="GET">
        <input type="hidden" name="page" value="hoa-hong"/>
        <input type="hidden" name="paged" value="1"/>
        <input type="hidden" name="tab" value="setting1"/>
        <input type="text" class="regular-text" placeholder="Điền tên user muốn tìm" name="username" value="<?php if (isset($_GET['username']) ? $_GET['username'] : ''); ?>" />
        <button type="submit" name="searchUser" class="button button-primary">Tìm kiếm</button>
      </form>
      <br />
      <table class="wp-list-table widefat fixed striped table-view-list users" style="min-width: 600px;">
        <thead>
          <tr>
            <th>Tên</th>
            <th>4. Chờ đối soát <br /> (sum commission status 4 (CURRENT USER))</th>
            <th>7. Thực nhận <br /> (sum commission status 2 (CURRENT USER))</th>
            <th>1. Hoa hồng <br /> (sum commission status 1 <b style="color: red;">(USER CON)</b> - sum(2 CURRENT USER) - sum(4 CURRENT USER))</th>
            <th>5. Tổng hoa hồng <br /> sum (commision status 1 (<b style="color: red;">USER CON</b>))</th>
            <th>6. Tổng hoa hồng đã rút <br /> (sum commission status 2 (CURRENT USER))</th>
            <th>2. Tổng doanh thu <br /> sum (total_order status 1 (<b style="color: red;">USER CON</b>))</th>
            <th>3. Tổng đơn hàng <br /> sum (row status 1 (<b style="color: red;">USER CON:</b>))</th>
            <th>User con</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ( $usersDisplay as $user ) { 
            $waitingReview = $wpdb->get_results('SELECT SUM(commission) as waitingReview FROM ' . $tableUserCommission . ' WHERE user_id = ' . $user['ID'] . ' AND status = ' . $status['USE_POINT_IN_PROCESS']);
            $actuallyReceive = $wpdb->get_results('SELECT SUM(commission) as actuallyReceive FROM ' . $tableUserCommission . ' WHERE user_id = ' . $user['ID'] . ' AND status = ' . $status['USE_POINT']);
            
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
            $totalRevenue = 0;
            foreach ($users as $userChild) {
              $checkUserParent = get_user_meta($userChild['ID'], 'user_parent', true);
              if ($user['ID'] === $checkUserParent) {
                foreach ($userCommissions as $commission4) {
                  if ($commission4['user_id'] === $userChild['ID'] && $commission4['status'] == $status['PURCHASE']) {
                    $childCommissions += $commission4['commission'];
                    $totalRevenue += $commission4['total_order'];
                  }
                }
              }
            }
          ?>
            <tr>
              <td><?php echo $user['user_login'] ?></td>
              <td><?php echo $waitingReview[0]->waitingReview; ?></td>
                <td><?php echo $actuallyReceive[0]->actuallyReceive; ?></td>
                <td><?php echo count($finalResult) > 0 ? $finalResult[$user['ID']]['commission'] : ''; ?></td>
                <td><?php echo $childCommissions; ?></td>
                <td><?php echo $actuallyReceive[0]->actuallyReceive; ?></td>
                <td><?php echo $totalRevenue; ?></td>
                <td><?php echo $totalOrder; ?></td>
              <td>
                <button type="button" class="button" onclick="openLowerModal(<?php echo $user['ID']; ?>)">Hiển thị cấp dưới</button>
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
      <form action="?page=hoa-hong&paged=1&tab=setting2" method="POST">
        <input type="number" class="regular-text" name="wp_affliate" value="<?php echo get_option('wp_affliate'); ?>" />
        <button type="submit" class="button button-primary" name="saveSetting">Lưu lại</button>
      </form>
    </div>
    <div id="tab-setting-3-content" class="tab-pane-affliate">
      <?php require_once(dirname(__FILE__) . '/tranfer.php'); ?>
    </div>
    <div id="tab-setting-4-content" class="tab-pane-affliate">
      <br />
      <form action="?page=hoa-hong&paged=1&tab=setting4" method="GET">
        <input type="hidden" name="page" value="hoa-hong"/>
        <input type="hidden" name="paged" value="1"/>
        <input type="hidden" name="tab" value="setting4"/>
        <b>Từ ngày</b>
        <select name="dateFrom" onchange="handleSubmit()">
          <option value="" disabled selected>Chọn ngày</option>
          <?php
            for ($i = 1; $i <= 31; $i++) { ?>
              <option <?php echo isset($_GET['dateFrom']) && $_GET['dateFrom'] == $i ? 'selected' : '' ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
          <?php }?>
        </select>
        <select name="monthFrom" onchange="handleSubmit()">
          <option value="" disabled selected>Chọn tháng</option>
          <?php
            for ($i = 1; $i <= 12; $i++) { ?>
              <option <?php echo isset($_GET['monthFrom']) && $_GET['monthFrom'] == $i ? 'selected' : '' ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
          <?php }?>
        </select>
        <select name="yearFrom" onchange="handleSubmit()">
          <option value="" disabled selected>Chọn năm</option>
          <?php
            $currentYear = date("Y");
            for ($i = $currentYear - 10; $i <= $currentYear; $i++) { ?>
              <option <?php echo isset($_GET['yearFrom']) && $_GET['yearFrom'] == $i ? 'selected' : '' ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
          <?php }?>
        </select>
        &nbsp;&nbsp;&nbsp;<b>Đến ngày</b>
        <select name="dateTo" onchange="handleSubmit()">
          <option value="" disabled selected>Chọn ngày</option>
          <?php
            for ($i = 1; $i <= 31; $i++) { ?>
              <option <?php echo isset($_GET['dateTo']) && $_GET['dateTo'] == $i ? 'selected' : '' ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
          <?php }?>
        </select>
        <select name="monthTo" onchange="handleSubmit()">
          <option value="" disabled selected>Chọn tháng</option>
          <?php
            for ($i = 1; $i <= 12; $i++) { ?>
              <option <?php echo isset($_GET['monthTo']) && $_GET['monthTo'] == $i ? 'selected' : '' ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
          <?php }?>
        </select>
        <select name="yearTo" onchange="handleSubmit()">
          <option value="" disabled selected>Chọn năm</option>
          <?php
            $currentYear = date("Y");
            for ($i = $currentYear - 10; $i <= $currentYear; $i++) { ?>
              <option <?php echo isset($_GET['yearTo']) && $_GET['yearTo'] == $i ? 'selected' : '' ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
          <?php }?>
        </select>
        <button type="submit" name="userFilter" class="button button-primary submitFilter" disabled>Lọc</button>
      </form>
      <br />
      <table class="wp-list-table widefat fixed striped table-view-list users" style="min-width: 600px;">
        <thead>
          <tr>
            <th>Tên</th>
            <th>6. Tổng hoa hồng đã rút <br /> (sum commission status 2 (CURRENT USER))</th>
            <th>2. Tổng doanh thu <br /> sum (total_order status 1 (<b style="color: red;">USER CON</b>))</th>
            <th>3. Tổng đơn hàng <br /> sum (row status 1 (<b style="color: red;">USER CON:</b>))</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          
          foreach ( $usersDisplay as $userFilter ) {
            $queryActuallyRecieve = 'SELECT SUM(commission) as actuallyReceive FROM ' . $tableUserCommission . ' WHERE user_id = ' . $userFilter['ID'] . ' AND status = ' . $status['USE_POINT'];
            if (isset($_GET['userFilter'])) {
              $queryActuallyRecieve .= " AND STR_TO_DATE(CONCAT_WS('/', `date`, `month`, `year`), '%d/%m/%Y') BETWEEN '" . $_GET['yearFrom'] . '-' . $_GET['monthFrom'] . '-' . $_GET['dateFrom'] . "'" . " AND '" . $_GET['yearTo'] . '-' . $_GET['monthTo'] . '-' . $_GET['dateTo'] . "'";
            }
            $actuallyReceive = $wpdb->get_results($queryActuallyRecieve);

            $queryUserCommission = 'SELECT * FROM ' . $tableUserCommission;
            if (isset($_GET['userFilter'])) {
              $queryUserCommission .= " WHERE STR_TO_DATE(CONCAT_WS('/', `date`, `month`, `year`), '%d/%m/%Y') BETWEEN '" . $_GET['yearFrom'] . '-' . $_GET['monthFrom'] . '-' . $_GET['dateFrom'] . "'" . " AND '" . $_GET['yearTo'] . '-' . $_GET['monthTo'] . '-' . $_GET['dateTo'] . "'";
            }

            $userCommissions = $wpdb->get_results( $queryUserCommission . ' ORDER BY id ASC', ARRAY_A );
            // echo $queryUserCommission; die();

            $commissions = [];
            foreach ($userCommissions as $commission1) {
              if ($userFilter['ID'] === $commission1['user_id']) {
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
        
            $totalOrder = 0;
            foreach ($result as $key => $value) {
              foreach ($value as $val2) {
                if ($val2['status'] == $status['PURCHASE']) {
                  $totalOrder++;
                }
              }
            }
        
            $totalRevenue = 0;
            foreach ($users as $userChild) {
              $checkUserParent = get_user_meta($userChild['ID'], 'user_parent', true);
              if ($userFilter['ID'] === $checkUserParent) {
                foreach ($userCommissions as $commission4) {
                  if ($commission4['user_id'] === $userChild['ID'] && $commission4['status'] == $status['PURCHASE']) {
                    $totalRevenue += $commission4['total_order'];
                  }
                }
              }
            }
          ?>
            <tr>
              <td><?php echo $userFilter['user_login'] ?></td>
              <td><?php echo $actuallyReceive[0]->actuallyReceive; ?></td>
              <td><?php echo $totalRevenue; ?></td>
              <td><?php echo $totalOrder; ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
      <ul class="pagination">
        <?php
          if ( (! empty( $_GET['paged'] )) && ($_GET['tab'] == 'setting4') ) $pg = $_GET['paged'];
          else $pg = 1;

          if ( isset( $pg ) && $pg > 1 ) {
            echo '<li><a class="button" href="'.site_url().'/wp-admin/admin.php?page=hoa-hong&paged=' . ( $pg - 1 ) . '&tab=setting4">«</a></li>';
          }

          for ( $i = 1; $i <= $totalPages; $i++ ) {
            if ( isset( $pg ) && $pg == $i )  $active = 'active';
            else $active = '';
            echo '<li><a href="'.site_url().'/wp-admin/admin.php?page=hoa-hong&paged=' . $i . '&tab=setting4" class="button ' . $active . '">' . $i . '</a></li>';
          }

          if ( isset( $pg ) && $pg < $totalPages ) {
            echo '<li><a class="button" href="'.site_url().'/wp-admin/admin.php?page=hoa-hong&paged=' . ( $pg + 1 ). '&tab=setting4">»</a></li>';
          }
        ?>
      </ul>
    </div>
  </div>
  <div class="overlay d-none"></div>
  <?php foreach ( $users as $user ) {
    $childUser = array();
    foreach ($users as $user1) {
      $checkParent =  get_user_meta($user1['ID'], 'user_parent', true);
      if ($checkParent && $checkParent == $user['ID']) {
        array_push($childUser, $user1);
      }
    }
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
                <th>Tổng hoa hồng <br /> sum (commision status 1 (<b style="color: red;">USER CON</b>))</th>
                <th>Tổng doanh thu <br /> sum (total_order status 1 (<b style="color: red;">USER CON</b>))</th>
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
</div>
