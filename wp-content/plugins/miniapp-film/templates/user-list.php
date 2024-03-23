<?php
  $currentPage = (! empty( $_GET['paged'] )) && ($_GET['tab'] == 'setting2') ? (int) $_GET['paged'] : 1;
  $paged = isset($_GET['paged']) ? $_GET['paged'] : 1;
  $stt = $paged - 1;
  $total = count( $users );
  $perPage = 10;                                                                                                                                                   ;
  $totalPages = ceil($total/ $perPage);
  $currentPage = max($currentPage, 1);
  $currentPage = min($currentPage, $totalPages);
  $offset = ($currentPage - 1) * $perPage;
  if ($offset < 0) $offset = 0;
  $users = array_slice($users, $offset, $perPage);
  $films = $wpdb->get_results('SELECT * FROM ' . $tableFilms . ' ORDER BY id DESC', ARRAY_A);
?>

<table class="wp-list-table widefat fixed striped table-view-list users">
  <thead>
    <tr>
      <th>Tên</th>
      <th>Hành động</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($users as $user) {
      $meta = get_user_meta($user['ID'], '_episode_list');
    ?>
      <tr>
        <td><?php echo $user['user_nicename'] . ' - ' . $user['user_login']; ?></td>
        <td>
          <button onclick="openBuyFilmModal('<?php echo $user['ID']; ?>')" class="button">Hiện tập phim đã mua</button>
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>
<?php foreach ($users as $user) {
    $meta = get_user_meta($user['ID'], '_episode_list');
  ?>
    <div id="modal-list-favorite-film-<?php echo $user['ID']; ?>" class="film-modal d-none">
      <div class="modal-wrapper">
        <p onclick="hideModal('modal-list-favorite-film-<?php echo $user['ID']; ?>')" class="close">✕</p>
        <div class="modal-header">
          <p>Danh sách tập phim đã mua</p>
        </div>
        <div class="modal-content">
          <div style="overflow-x:auto;">
            <table class="wp-list-table widefat striped table-view-list">
              <thead>
                <tr>
                  <th>Tập phim đã mua</th>
                  <th>Phim</th>
                </tr>
              </thead>
              <tbody>
                  <?php
                    if (count($meta) > 0) {
                      foreach ($meta[0] as $key => $value) {
                        $post = get_post($value);
                        $filmId = get_post_meta($post->ID, '_film_selected', true);
                      ?>
                      <tr>
                        <td>
                            <?php echo $post->post_title; ?>
                          </td>
                          <td>
                            <?php
                              foreach ($films as $film) {
                                if ($film['id'] == $filmId) {
                                  echo $film['film_name'];
                                  if (!empty($film['film_season'])) {
                                    echo ' - Phần ' . $film['film_season'];
                                  }
                                }
                              }
                            ?>
                          </td>
                        </tr>
                      <?php }
                    } else { ?>
                      <tr>
                        <td>
                          <p>Chưa có tập phim đã mua</p>
                        </td>
                        <td></td>
                      </tr>
                   <?php }
                  ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  <?php } ?>
<ul class="pagination">
  <?php
    if ( (! empty( $_GET['paged'] )) && ($_GET['tab'] == 'setting2') ) $pg = $_GET['paged'];
    else $pg = 1;

    if ( isset( $pg ) && $pg > 1 ) {
      echo '<li><a class="button" href="'.site_url().'/wp-admin/admin.php?page=danh-sach-phim&paged=' . ( $pg - 1 ) . '&tab=setting2">«</a></li>';
    }

    for ( $i = 1; $i <= $totalPages; $i++ ) {
      if ( isset( $pg ) && $pg == $i )  $active = 'active';
      else $active = '';
      echo '<li><a href="'.site_url().'/wp-admin/admin.php?page=danh-sach-phim&paged=' . $i . '&tab=setting2" class="button ' . $active . '">' . $i . '</a></li>';
    }

    if ( isset( $pg ) && $pg < $totalPages ) {
      echo '<li><a class="button" href="'.site_url().'/wp-admin/admin.php?page=danh-sach-phim&paged=' . ( $pg + 1 ). '&tab=setting2">»</a></li>';
    }
  ?>
</ul>
