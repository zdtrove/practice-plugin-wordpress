<?php
global $wpdb;
$tableFilms = $wpdb->prefix . 'films';
$tableUser = $wpdb->prefix . 'users';
$successMessage = '';

$categories = get_categories(array('hide_empty' => 0, 'taxonomy' => 'product_cat'));
$users = $wpdb->get_results( 'SELECT * FROM ' . $tableUser . ' ORDER BY id ASC', ARRAY_A );

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
    'discount' => $_POST['discount'],
  );

  $wpdb->insert($tableFilms, $arrayInsert);
  $successMessage = 'Thêm phim thành công';
}

if (isset($_POST['editFilm'])) {
  $arrayUpdate = array(
    'category_id' => $_POST['category_id'],
    'category_name' => getCategoryName($categories, $_POST['category_id']),
    'film_name' => $_POST['film_name'],
    'film_poster' => $_POST['film_poster'],
    'discount' => $_POST['discount'],
  );

  $wpdb->update($tableFilms, $arrayUpdate, array('id' => $_POST['filmId']));
}

if (isset($_POST['updateEpisode'])) {
  update_post_meta($_POST['filmId'], '_film_episode', $_POST['_film_video']);
}

$filmsDisplay = [];
$films = $wpdb->get_results('SELECT * FROM ' . $tableFilms . ' ORDER BY id DESC', ARRAY_A);
?>

<div class="wrap">
  <?php if ($successMessage) { ?>
    <div id="message" class="success-message">
      <p><?php echo $successMessage; ?></p>
      <button id="remove-message" type="button"></button>
    </div>
  <?php } ?>
  <ul class="nav-tabs-film">
    <li id="tabSetting1" class="active" onclick="changeUrl(1)">
      <a href="#tab-setting-1-content">Quản lý phim</a>
    </li>
    <li id="tabSetting2" onclick="changeUrl(2)">
      <a href="#tab-setting-2-content">Quản lý user</a>
    </li>
  </ul>
  <div class="tab-content">
    <div id="tab-setting-1-content" class="tab-pane-film active">
      <?php require_once(dirname(__FILE__) . '/film-list.php'); ?>
    </div>
    <div id="tab-setting-2-content" class="tab-pane-film">
    <?php require_once(dirname(__FILE__) . '/user-list.php'); ?>
    </div>
  </div>
  <?php require_once(dirname(__FILE__) . '/modal-add-film.php'); ?>
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
        <form id="form-edit-film-<?php echo $film['id']; ?>" action="" method="POST">
          <div class="modal-content">
            <div style="overflow-x:auto;">
              <table class="wp-list-table widefat striped table-view-list">
                <thead>
                  <tr>
                    <th>Tên phim</th>
                    <th>Poster phim</th>
                    <th>Chiết khấu</th>
                    <th>Category</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <p>Tên phim</p>
                      <input type="text" class="require-field" name="film_name" placeholder="Vui lòng nhập tên phim" value="<?php echo $film['film_name']; ?>" />
                      <p class="required d-none">Đây là trường bắt buộc</p>
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
                      <input type="number" name="discount" value="<?php echo $film['discount']; ?>" />
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
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-actions">
            <input hidden type="text" name="filmId" value="<?php echo $film['id']; ?>" />
            <input hidden type="text" name="editFilm" value="<?php echo $film['id']; ?>" />
            <button onclick="submitForm('form-edit-film-<?php echo $film['id']; ?>')" type="button" class="button button-primary">Cập nhật</button>
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