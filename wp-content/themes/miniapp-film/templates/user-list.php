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
      <th>Tập phim đã mua</th>
      <th>Danh sách phim yêu thích</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($users as $user) {
      $meta = get_user_meta($user['ID'], '_episode_list');
      $favorite = get_user_meta($user['ID'], '_favorite_list');
    ?>
      <tr>
        <td><?php echo $user['user_nicename'] . ' - ' . $user['user_login']; ?></td>
        <td>
          <?php
            if (count($meta) > 0) {
              foreach ($meta[0] as $key => $value) {
                $post = get_post($value);
                $filmId = get_post_meta($post->ID, '_film_selected', true);
              ?>
                <p>
                  <?php echo $post->post_title; ?> - 
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
                </p>
              <?php }
            }
          ?>
        </td>
        <td>
            <?php
              if (count($favorite) > 0) {
                foreach ($favorite[0] as $key => $value) {?>
                  <p>
                    <?php
                      echo $films[$value]['film_name'];
                      if (!empty($films[$value]['film_season'])) {
                        echo ' - Phần ' . $films[$value]['film_season'];
                      }
                    ?>
                  </p>
                <?php }
              }
            ?>
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>
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
