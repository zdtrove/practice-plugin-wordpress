<table class="wp-list-table widefat fixed striped table-view-list users">
  <thead>
    <tr>
      <th>Tên</th>
      <th>Tập phim đã mua</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($users as $user) {
      $meta = get_user_meta($user['ID'], '_episode_list');
    ?>
      <tr>
        <td><?php echo $user['user_nicename']; ?></td>
        <td>
          <?php
            if (count($meta) > 0) {
              foreach ($meta[0] as $key => $value) {
                $post = get_post($value);
                $filmId = get_post_meta($post->ID, '_film_selected', true);
              ?>
                <p><?php echo $post->post_title; ?> - <?php echo $films[$filmId]['film_name']; ?></p>
              <?php }
            }
          ?>
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>
