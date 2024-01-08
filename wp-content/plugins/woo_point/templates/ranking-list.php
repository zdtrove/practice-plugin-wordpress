<?php
  $users = $wpdb->get_results( 'SELECT * FROM wp_woo_rank ORDER BY id ASC', ARRAY_A );
  $currentPage = ! empty( $_GET['paged'] ) ? (int) $_GET['paged'] : 1;
  $total = count( $users );
  $perPage = 10;
  $totalPages = ceil($total/ $perPage);
  $currentPage = max($currentPage, 1);
  $currentPage = min($currentPage, $totalPages);
  $offset = ($currentPage - 1) * $perPage;
  if ($offset < 0) $offset = 0;
  $users = array_slice($users, $offset, $perPage);
?>

<table class="wp-list-table widefat fixed striped table-view-list users">
  <thead>
    <tr>
      <td class="manage-column column-cb check-column">
        <input type="checkbox" />
      </td>
      <th>Hình ảnh</th>
      <th>Xếp hạng</th>
      <th>Chi tiêu tối thiểu</th>
      <th>Khuyến mãi</th>
      <th>Hành động</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ( $users as $key => $value ) { ?>
      <tr>
        <th class="check-column">
          <input type="checkbox" />
        </th>
        <td style="max-width: 50px;">
          <img src="<?php echo $value['imageurl'] ?>" alt="" width="50px"/>
        </td>
        <td><?php echo $value['name'] ?></td>
        <td><?php echo $value['minimum_spending'] ?></td>
        <td><?php echo $value['price_sale_off'] ?></td>
        <td class="table-actions">
          <span class="button dashicons dashicons-edit-page"></span>
          <span class="button delete">✕</span>
        </td>
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
