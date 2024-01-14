<?php
  global $wpdb;
  $successMessage = '';
  $errorMessage = '';
  $tableUser = $wpdb->prefix . 'users';

  $users = $wpdb->get_results( 'SELECT * FROM ' . $tableUser . ' ORDER BY id ASC', ARRAY_A );

  $currentPage = (! empty( $_GET['paged'] )) && ($_GET['tab'] == 'dstv') ? (int) $_GET['paged'] : 1;
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
          <?php foreach ( $users as $key => $user ) { ?>
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
  <?php foreach ( $users as $key => $user ) { ?>
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
                <th>Tên</th>
                <th>Cấp</th>
                <th>Doanh thu</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>User 1</td>
                <td>Cấp 1</td>
                <td>1.000.000</td>
              </tr>
              <tr>
                <td>User 2</td>
                <td>Cấp 1</td>
                <td>1.000.000</td>
              </tr>
              <tr>
                <td>User 3</td>
                <td>Cấp 2</td>
                <td>1.000.000</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  <?php } ?>
  <?php foreach ( $users as $key => $user ) { ?>
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
                <th>Doanh thu</th>
                <th>Hoa hồng</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>2.000.000</td>
                <td>1.000.000</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  <?php } ?>
</div>
