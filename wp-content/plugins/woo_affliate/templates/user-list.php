<br />
<form action="?page=hoa-hong&paged=1&tab=setting1" method="GET">
  <input type="hidden" name="page" value="hoa-hong"/>
  <input type="hidden" name="paged" value="1"/>
  <input type="hidden" name="tab" value="setting1"/>
  <input type="text" maxlength="48" class="regular-text" placeholder="Điền tên user muốn tìm" name="username" value="<?php echo isset($_GET['username']) ? $_GET['username'] : ''; ?>" />
  <button type="submit" name="searchUser" class="button button-primary">Tìm kiếm</button>
</form>
<br />
<table class="wp-list-table widefat fixed striped table-view-list users">
  <thead>
    <tr>
      <th>Tên</th>
      <th>Chờ đối soát<span class="d-none"><br /> (sum commission status 4 (CURRENT USER))</span></th>
      <th>Thực nhận <span class="d-none"><br /> (sum commission status 2 (CURRENT USER))</span></th>
      <th>Hoa hồng <span class="d-none"><br /> tổng commission status 1 <b style="color: red;">(USER CON)</b> <br /> - tổng (commission status 2 <b style="color: blue">CURRENT USER</b>) <br /> - tổng (commission status 4 <b style="color: blue">CURRENT USER</b>))</span></th>
      <th>Tổng hoa hồng <span class="d-none"><br /> sum (commision status 1 (<b style="color: red;">USER CON</b>))</span></th>
      <th>Tổng hoa hồng đã rút <span class="d-none"><br /> (sum commission status 2 (CURRENT USER))</span></th>
      <th>Tổng doanh thu <span class="d-none"><br /> sum (total_order status 1 (<b style="color: red;">USER CON</b>))</span></th>
      <th>Tổng đơn hàng <span class="d-none"><br /> <b style="color: green">tổng số dòng (row) </b>status 1 (<b style="color: red;">USER CON</b>)</span></th>
      <th>User con</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ( $usersDisplay as $keyUser => $user ) { 
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
  
      $commissionParent = 0;
      foreach ($result as $key => $value) {
        foreach ($value as $val2) {
          if ($val2['status'] == $status['USE_POINT'] || $val2['status'] == $status['USE_POINT_IN_PROCESS']) {
            $commissionParent += $val2['commission'];
          }
        }
      }
  
      $childCommissions = 0;
      $totalRevenue = 0;
      $totalOrder = 0;
      foreach ($users as $userChild) {
        $checkUserParent = get_user_meta($userChild['ID'], 'user_parent', true);
        if ($user['ID'] === $checkUserParent) {
          foreach ($userCommissions as $commission4) {
            if ($commission4['user_id'] === $userChild['ID'] && $commission4['status'] == $status['PURCHASE']) {
              $childCommissions += $commission4['commission'];
              $totalRevenue += $commission4['total_order'];
              $totalOrder++;
            }
          }
          foreach ($users as $userChildLevel2) {
            $checkUserParentLevel2 = get_user_meta($userChildLevel2['ID'], 'user_parent', true);
            if ($userChild['ID'] === $checkUserParentLevel2) {
              foreach ($userCommissions as $commission5) {
                if ($commission5['user_id'] === $userChildLevel2['ID'] && $commission5['status'] == $status['PURCHASE']) {
                  $childCommissions += $commission5['commission'];
                  $totalRevenue += $commission5['total_order'];
                  $totalOrder++;
                }
              }
            }
          }
        }
      }
    ?>
      <tr>
        <td><?php echo $user['user_nicename'] . ' - ' . $user['user_login'] ?></td>
        <td><?php echo $waitingReview[0]->waitingReview; ?></td>
        <td><?php echo $actuallyReceive[0]->actuallyReceive; ?></td>
        <td><?php echo $childCommissions - $commissionParent  ?></td>
        <td><?php echo $childCommissions; ?></td>
        <td><?php echo $actuallyReceive[0]->actuallyReceive; ?></td>
        <td><?php echo $totalRevenue; ?></td>
        <td><?php echo $totalOrder; ?></td>
        <td>
          <button type="button" class="button" onclick="openLowerModal(<?php echo $keyUser; ?>)">Hiển thị cấp dưới</button>
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>
<ul class="pagination">
  <?php
    if ( (! empty( $_GET['paged'] )) && ($_GET['tab'] == 'setting1') ) $pg = $_GET['paged'];
    else $pg = 1;
    $paramFilter = isset($_GET['searchUser']) && isset($_GET['username']) ? '&username=' . $_GET['username'] . '&searchUser' : '';

    if ( isset( $pg ) && $pg > 1 ) {
      echo '<li><a class="button" href="'.site_url().'/wp-admin/admin.php?page=hoa-hong&paged=' . ( $pg - 1 ) . '&tab=setting1' . $paramFilter . '">«</a></li>';
    }

    for ( $i = 1; $i <= $totalPages; $i++ ) {
      if ( isset( $pg ) && $pg == $i )  $active = 'active';
      else $active = '';
      echo '<li><a href="'.site_url().'/wp-admin/admin.php?page=hoa-hong&paged=' . $i . '&tab=setting1' . $paramFilter . '" class="button ' . $active . '">' . $i . '</a></li>';
    }

    if ( isset( $pg ) && $pg < $totalPages ) {
      echo '<li><a class="button" href="'.site_url().'/wp-admin/admin.php?page=hoa-hong&paged=' . ( $pg + 1 ). '&tab=setting1' . $paramFilter . '">»</a></li>';
    }
  ?>
</ul>
