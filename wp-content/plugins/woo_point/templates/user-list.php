<?php
  $table = $wpdb->prefix . 'users';
  $tableHistory = $wpdb->prefix . 'woo_history_user_point';
  $users = $wpdb->get_results( 'SELECT ID, display_name FROM ' . $table . ' ORDER BY id ASC', ARRAY_A );
  $currentPage = ! empty( $_GET['paged'] ) ? (int) $_GET['paged'] : 1;
  $total = count( $users );
  $perPage = 10;
  $totalPages = ceil($total/ $perPage);
  $currentPage = max($currentPage, 1);
  $currentPage = min($currentPage, $totalPages);
  $offset = ($currentPage - 1) * $perPage;
  if ($offset < 0) $offset = 0;
  $users = array_slice($users, $offset, $perPage);
  $history = $wpdb->get_results( 'SELECT * FROM ' . $tableHistory . ' ORDER BY id ASC', ARRAY_A);

  $listHistory = [];
  foreach ($users as $key1 => $user) {
    foreach ($history as $key2 => $his) {
      if ($user['ID'] === $his['user_id']) {
        array_push($listHistory, array('id' => $his['user_id'], 'status' => $his['status'], 'point' => $his['point']));
      }
    }
  }

  echo '<pre>';
    print_r($listHistory);
  echo '</pre>';

  $result = array();
  foreach ($listHistory as $element) {
    $result[$element['id']][] = $element;
  }

  echo '<pre>';
    print_r($result);
  echo '</pre>';
?>

<table class="wp-list-table widefat fixed striped table-view-list users">
  <thead>
    <tr>
      <th>Tên</th>
      <th>Xếp hạng</th>
      <th>Điểm chi tiêu</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ( $users as $key => $value ) { ?>
      <tr>
        <td><?php echo $value['display_name'] ?></td>
        <td>Chưa có xếp hạng</td>
        <td>0đ</td>
      </tr>
    <?php } ?>
  </tbody>
</table>
<ul class="pagination">
  <?php
    if ( !empty( $_GET['paged'] ) ) $pg = $_GET['paged'];

    if ( isset( $pg ) && $pg > 1 ) {
      echo '<li><a class="button" href="'.site_url().'/wp-admin/admin.php?page=tich-diem&paged=' . ( $pg - 1 ) . '">«</a></li>';
    }

    for ( $i = 1; $i <= $totalPages; $i++ ) {
      if ( isset( $pg ) && $pg == $i )  $active = 'active';
      else $active = '';
      echo '<li><a href="'.site_url().'/wp-admin/admin.php?page=tich-diem&paged=' . $i . '" class="button ' . $active . '">' . $i . '</a></li>';
    }

    if ( isset( $pg ) && $pg < $totalPages ) {
      echo '<li><a class="button" href="'.site_url().'/wp-admin/admin.php?page=tich-diem&paged=' . ( $pg + 1 ). '">»</a></li>';
    }
  ?>
</ul>