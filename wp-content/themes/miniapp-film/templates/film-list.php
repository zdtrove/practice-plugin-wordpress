<?php
  $filmsDisplay = $films;
  $currentPage = (! empty( $_GET['paged'] )) && ($_GET['tab'] == 'setting1') ? (int) $_GET['paged'] : 1;
  $paged = isset($_GET['paged']) ? $_GET['paged'] : 1;
  $stt = $paged - 1;
  $total = count( $filmsDisplay );
  $perPage = 2;                                                                                                                                                   ;
  $totalPages = ceil($total/ $perPage);
  $currentPage = max($currentPage, 1);
  $currentPage = min($currentPage, $totalPages);
  $offset = ($currentPage - 1) * $perPage;
  if ($offset < 0) $offset = 0;
  $filmsDisplay = array_slice($filmsDisplay, $offset, $perPage);
?>

<div>
  <button class="button button-primary button-add-film">
    <span>Thêm phim</span>
  </button>
</div>
<br />
<table class="wp-list-table widefat fixed striped table-view-list">
  <thead>
    <tr>
      <th>Tên phim</th>
      <th>Poster phim</th>
      <th>Chiết khấu</th>
      <th>Category</th>
      <th>Hành động</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($filmsDisplay as $film) { ?>
      <tr>
        <td><?php echo $film['film_name']; ?></td>
        <td style="max-width: 50px;">
          <img src="<?php echo $film['film_poster'] ?>" alt="" width="50px" />
        </td>
        <td>
          <p><?php echo $film['discount']; ?></p>
        </td>
        <td><?php echo $film['category_name']; ?></td>
        <td style="display: flex; gap: 5px; flex-wrap: wrap;">
          <button onclick="openEditModal('<?php echo $film['id']; ?>')" class="button">Chỉnh sửa</button>
          <button onclick="openEpisodeModal('<?php echo $film['id']; ?>')" class="button">Thêm video phim</button>
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
      echo '<li><a class="button" href="'.site_url().'/wp-admin/admin.php?page=danh-sach-phim&paged=' . ( $pg - 1 ) . '&tab=setting1">«</a></li>';
    }

    for ( $i = 1; $i <= $totalPages; $i++ ) {
      if ( isset( $pg ) && $pg == $i )  $active = 'active';
      else $active = '';
      echo '<li><a href="'.site_url().'/wp-admin/admin.php?page=danh-sach-phim&paged=' . $i . '&tab=setting1" class="button ' . $active . '">' . $i . '</a></li>';
    }

    if ( isset( $pg ) && $pg < $totalPages ) {
      echo '<li><a class="button" href="'.site_url().'/wp-admin/admin.php?page=danh-sach-phim&paged=' . ( $pg + 1 ). '&tab=setting1">»</a></li>';
    }
  ?>
</ul>
