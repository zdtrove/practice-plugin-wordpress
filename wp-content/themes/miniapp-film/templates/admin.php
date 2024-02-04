<?php
global $wpdb;
$tableFilms = $wpdb->prefix . 'films';

$categories = get_categories(array('hide_empty' => 0));

function getCategoryName($categories, $id)
{
  foreach ($categories as $category) {
    if ($category->term_id == $id) {
      return $category->cat_name;
    }
  }
}

if (isset($_POST['addFilm'])) {
  $arrayInsert = array(
    'category_id' => $_POST['category_id'],
    'category_name' => getCategoryName($categories, $_POST['category_id']),
    'film_name' => $_POST['film_name'],
    'film_poster' => $_POST['film_poster'],
    'combo_5' => $_POST['combo_5'],
    'combo_10' => $_POST['combo_10'],
    'combo_15' => $_POST['combo_15'],
    'combo_20' => $_POST['combo_20'],
  );

  $wpdb->insert($tableFilms, $arrayInsert);
}

if (isset($_POST['editFilm'])) {
  $arrayUpdate = array(
    'category_id' => $_POST['category_id'],
    'category_name' => getCategoryName($categories, $_POST['category_id']),
    'film_name' => $_POST['film_name'],
    'film_poster' => $_POST['film_poster'],
    'combo_5' => $_POST['combo_5'],
    'combo_10' => $_POST['combo_10'],
    'combo_15' => $_POST['combo_15'],
    'combo_20' => $_POST['combo_20'],
  );

  $wpdb->update($tableFilms, $arrayUpdate, array('id' => $_POST['filmId']));
}

if (isset($_POST['updateEpisode'])) {
  update_post_meta($_POST['filmId'], '_film_episode', $_POST['_film_video']);
}

$films = $wpdb->get_results('SELECT * FROM ' . $tableFilms . ' ORDER BY id ASC', ARRAY_A);
?>

<div class="wrap">
  <h1>Quản lý phim</h1>
  <hr />
  <br />
  <div>
    <button class="button button-primary button-add-film">
      <span>Thêm phim</span>
    </button>
  </div>
  <br />
  <?php require_once(dirname(__FILE__) . '/modal-add.php'); ?>
  <table class="wp-list-table widefat fixed striped table-view-list">
    <thead>
      <tr>
        <th>Tên phim</th>
        <th>Poster phim</th>
        <th>Category</th>
        <th>Hành động</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($films as $film) { ?>
        <tr>
          <td><?php echo $film['film_name']; ?></td>
          <td style="max-width: 50px;">
            <img src="<?php echo $film['film_poster'] ?>" alt="" width="50px" />
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
  <div id="overlay" class="overlay d-none"></div>
  <?php foreach ($films as $key => $film) {
    $args = array(
      'meta_key' => '_film_selected',
      'meta_value' => $film['id'],
      'post_type' => 'product',
      'post_status' => 'any',
    );
    $episodeList = get_posts($args);
  ?>
    <div id="modal-edit-film-<?php echo $film['id']; ?>" class="film-modal d-none">
      <div class="modal-wrapper">
        <p onclick="hideModal('modal-edit-film-<?php echo $film['id']; ?>')" class="close">✕</p>
        <div class="modal-header">
          <p>Chỉnh sửa phim</p>
        </div>
        <form action="" method="POST">
          <div class="modal-content">
            <div style="overflow-x:auto;">
              <table class="wp-list-table widefat striped table-view-list">
                <thead>
                  <tr>
                    <th>Tên phim</th>
                    <th>Poster phim</th>
                    <th>Category</th>
                    <th>Giá combo 5 tập</th>
                    <th>Giá combo 10 tập</th>
                    <th>Giá combo 15 tập</th>
                    <th>Giá combo 20 tập</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <p>Tên phim</p>
                      <input type="text" name="film_name" placeholder="Vui lòng nhập tên phim" value="<?php echo $film['film_name']; ?>" />
                    </td>
                    <td>
                      <button type="button" class="upload-poster-button button flex-center">
                        <span class="dashicons dashicons-admin-media"></span>
                        <span>Đổi hình</span>
                      </button>
                      <input class="poster-url" type="text" hidden name="film_poster" value="<?php echo $film['film_poster']; ?>" />
                      <div class="poster-wrapper" style="width: 50px; height: auto; margin-top: 10px;">
                        <?php
                        if ($film['film_poster']) {
                          echo '<img src="' . $film['film_poster'] . '" alt="" style="width: 50px; height: auto;" />';
                        }
                        ?>
                      </div>
                    </td>
                    <td>
                      <p>Category</p>
                      <select name="category_id">
                        <?php foreach ($categories as $category) {
                          if ($category->cat_name != 'Uncategorized') {
                        ?>
                            <option <?php echo $category->term_id == $film['category_id'] ? 'selected' : '' ?> value="<?php echo $category->term_id; ?>"><?php echo $category->cat_name; ?></option>
                        <?php }
                        } ?>
                      </select>
                    </td>
                    <td>
                      <input style="width: 100px;" name="combo_5" type="number" value="<?php echo $film['combo_5']; ?>" />
                    </td>
                    <td>
                      <input style="width: 100px;" name="combo_10" type="number" value="<?php echo $film['combo_10']; ?>" />
                    </td>
                    <td>
                      <input style="width: 100px;" name="combo_15" type="number" value="<?php echo $film['combo_15']; ?>" />
                    </td>
                    <td>
                      <input style="width: 100px;" name="combo_20" type="number" value="<?php echo $film['combo_20']; ?>" />
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-actions">
            <input hidden type="text" name="filmId" value="<?php echo $film['id']; ?>" />
            <button type="submit" id="button-submit-edit-film-<?php echo $film['id']; ?>" class="button button-primary" name="editFilm">Cập nhật</button>
          </div>
        </form>
      </div>
    </div>
    <div id="modal-list-episode-<?php echo $film['id']; ?>" class="film-modal d-none">
      <div class="modal-wrapper">
        <p onclick="hideModal('modal-list-episode-<?php echo $film['id']; ?>')" class="close">✕</p>
        <div class="modal-header">
          <p>Thêm video cho tập phim</p>
        </div>
        <form action="" method="POST">
          <div class="modal-content">
            <div style="overflow-x:auto;">
              <table class="wp-list-table widefat striped table-view-list">
                <thead>
                  <tr>
                    <th>Tập phim</th>
                    <th>Video</th>
                    <th>Hành động</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($episodeList as $episode) { ?>
                    <tr>
                      <td>
                        <p><?php echo $episode->post_title; ?></p>
                      </td>
                      <td class="flex-center td-video-<?php echo $episode->ID; ?>">
                        <button type="button" class="upload-video-button button flex-center">
                          <span class="dashicons dashicons-admin-media"></span>
                          <span>Tải video</span>
                        </button>
                        <p style="margin-left: 15px;"><?php echo $episode->_film_episode; ?></p>
                      </td>
                      <td class="parent-td">
                        <form>
                          <input hidden type="text" name="filmId" value="<?php echo $episode->ID; ?>" />
                          <input hidden type="text" class="video-url" name="_film_video" />
                          <button type="submit" class="button button-primary" name="updateEpisode">Cập nhật</button>
                        </form>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </form>
      </div>
    </div>
  <?php } ?>
</div>