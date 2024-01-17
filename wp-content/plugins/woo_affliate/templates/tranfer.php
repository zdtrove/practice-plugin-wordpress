<?php
    $userCommissions = $wpdb->get_results( 'SELECT * FROM ' . $tableUserCommission . ' ORDER BY id ASC', ARRAY_A );
    $userCommissionsStatus4 = [];
    
    foreach ( $userCommissions as $userCommission ) {
      if ($userCommission['status'] == $status['USE_POINT_IN_PROCESS']) {
        $temp = $userCommission;
        $temp['display_name'] = get_user_meta($userCommission['user_id'], 'nickname', true);
        $temp['woo_aff_name'] = json_decode($userCommission['payment_method'])->name;
        $temp['woo_aff_stk'] = json_decode($userCommission['payment_method'])->stk;
        $temp['woo_aff_bankname'] = json_decode($userCommission['payment_method'])->bankname;
        array_push($userCommissionsStatus4, $temp);
      }
    }

    $currentPage = (! empty( $_GET['paged'] )) && ($_GET['tab'] == 'setting3') ? (int) $_GET['paged'] : 1;
    $total = count( $userCommissionsStatus4 );
    $perPage = 10;
    $totalPages = ceil($total/ $perPage);
    $currentPage = max($currentPage, 1);
    $currentPage = min($currentPage, $totalPages);
    $offset = ($currentPage - 1) * $perPage;
    if ($offset < 0) $offset = 0;
    $userCommissionsStatus4 = array_slice($userCommissionsStatus4, $offset, $perPage);
?>

<table class="wp-list-table widefat fixed striped table-view-list users">
  <thead>
    <tr>
      <th>Tên người dùng</th>
      <th>Tên người dùng (tài khoản)</th>
      <th>Số tài khoản</th>
      <th>Ngân hàng</th>
      <th>Số tiền</th>
      <th>Trạng thái</th>
    </tr>
  </thead>
  <tbody>
    <?php
      foreach ($userCommissionsStatus4 as $userCommissionStatus4) {
    ?>
      <tr>
        <td><?php echo $userCommissionStatus4['display_name'] ?></td>
        <td><?php echo $userCommissionStatus4['woo_aff_name'] ?></td>
        <td><?php echo $userCommissionStatus4['woo_aff_stk'] ?></td>
        <td><?php echo $userCommissionStatus4['woo_aff_bankname'] ?></td>
        <td><?php echo $userCommissionStatus4['commission'] ?></td>
        <td>
          <form action="?page=hoa-hong&paged=1&tab=setting3" method="POST">
            <select name="status">
              <option value="4">4</option>
              <option value="2">2</option>
            </select>
            <input hidden name="userCommissionId" value="<?php echo $userCommissionStatus4['id'] ?>" />
            <button type="submit" name="updateStatus" class="button">Cập nhật</button>
          </form>
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>
<ul class="pagination">
  <?php
    if ( (! empty( $_GET['paged'] )) && ($_GET['tab'] == 'setting3') ) $pg = $_GET['paged'];
    else $pg = 1;

    if ( isset( $pg ) && $pg > 1 ) {
      echo '<li><a class="button" href="'.site_url().'/wp-admin/admin.php?page=hoa-hong&paged=' . ( $pg - 1 ) . '&tab=setting3">«</a></li>';
    }

    for ( $i = 1; $i <= $totalPages; $i++ ) {
      if ( isset( $pg ) && $pg == $i )  $active = 'active';
      else $active = '';
      echo '<li><a href="'.site_url().'/wp-admin/admin.php?page=hoa-hong&paged=' . $i . '&tab=setting3" class="button ' . $active . '">' . $i . '</a></li>';
    }

    if ( isset( $pg ) && $pg < $totalPages ) {
      echo '<li><a class="button" href="'.site_url().'/wp-admin/admin.php?page=hoa-hong&paged=' . ( $pg + 1 ). '&tab=setting3">»</a></li>';
    }
  ?>
</ul>