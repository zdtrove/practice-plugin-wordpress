<?php
global $wpdb;
$tableFilms = $wpdb->prefix . 'films';
$tableUser = $wpdb->prefix . 'users';
$successMessage = '';

$categories = get_categories(array('hide_empty' => 0, 'taxonomy' => 'product_cat'));
$users = $wpdb->get_results( 'SELECT * FROM ' . $tableUser . ' ORDER BY id ASC', ARRAY_A );

if (isset($_POST['addFilm'])) {
  $arrayInsert = array(
    'category_ids' => json_encode($_POST['category_ids']),
    'film_name' => $_POST['film_name'],
    'film_poster' => $_POST['film_poster'],
    'film_season' => $_POST['film_season'],
    'film_parent' => $_POST['film_parent'],
    'film_description' => $_POST['film_description'],
    'discount' => $_POST['discount'],
  );

  $wpdb->insert($tableFilms, $arrayInsert);
  $successMessage = 'Thêm phim thành công';
}

if (isset($_POST['editFilm'])) {
  $arrayUpdate = array(
    'category_ids' => json_encode($_POST['category_ids']),
    'film_name' => $_POST['film_name'],
    'film_poster' => $_POST['film_poster'],
    'film_season' => $_POST['film_season'],
    'film_parent' => $_POST['film_parent'],
    'film_description' => $_POST['film_description'],
    'discount' => $_POST['discount'],
  );

  $wpdb->update($tableFilms, $arrayUpdate, array('id' => $_POST['filmId']));
  $successMessage = 'Chỉnh sửa phim thành công';
}

if (isset($_POST['updateEpisode'])) {
  update_post_meta($_POST['filmId'], '_film_episode', $_POST['_film_episode']);
  update_post_meta($_POST['filmId'], '_film_length', $_POST['_film_length']);

  $successMessage = 'Chỉnh sửa tập phim thành công';
}

if (isset($_POST['deleteFilm'])) {
  $wpdb->delete($tableFilms, array('id' => $_POST['filmId']));
  $args = array(
    'meta_key' => '_film_selected',
    'meta_value' => $_POST['filmId'],
    'post_type' => 'product',
    'post_status' => 'any',
  );
  $episodeListDelete = get_posts($args);
  
  foreach ($episodeListDelete as $postDelete) {
    wp_delete_post($postDelete->ID);
  }

  $successMessage = 'Xoá phim thành công';
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
      'numberposts' => -1,
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
                    <th>Phần phim</th>
                    <th>Poster phim</th>
                    <th>Mô tả phim</th>
                    <th>Chiết khấu</th>
                    <th>Phim cha</th>
                    <th>Categories</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <input type="text" class="require-field" name="film_name" placeholder="Vui lòng nhập tên phim" value="<?php echo $film['film_name']; ?>" />
                      <p class="required d-none">Đây là trường bắt buộc</p>
                    </td>
                    <td>
                      <div style="width: 80px;">
                        <input style="width: 100%;" min="1" oninput="this.value = Math.abs(this.value)" type="number" name="film_season" value="<?php echo $film['film_season']; ?>" />
                      </div>
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
                      <div style="width: 250px;">
                        <textarea style="width: 100%;" name="film_description" rows="5" cols="30"><?php echo $film['film_description']; ?></textarea>
                      </div>
                    </td>
                    <td>
                      <div style="width: 80px;">
                        <input style="width: 100%;" type="number" name="discount" value="<?php echo $film['discount']; ?>" min="0" oninput="this.value = Math.abs(this.value)" />
                      </div>
                      </td>
                    <td>
                    <select name="film_parent">
                      <option value="" <?php echo empty($film['film_season']) ? 'selected' : ''; ?>>Chọn phim cha</option>
                      <?php
                        foreach ($films as $filmParent) { ?>
                          <option <?php echo $film['film_parent'] == $filmParent['id'] ? 'selected' : '' ?> value="<?php echo $filmParent['id']; ?>"><?php echo $filmParent['film_name']; ?><?php echo !empty($filmParent['film_season']) ? ' - Phần ' . $filmParent['film_season'] : ''; ?></option>
                        <?php }
                      ?>
                    </select>
                    </td>
                    <td>
                      <div style="display: flex; justify-content: flex-start; flex-wrap: wrap; gap: 15px;">
                        <?php foreach ($categories as $category) {
                          if ($category->cat_name != 'Uncategorized') {
                        ?>
                          <div>
                            <input type="checkbox" name="category_ids[]" value="<?php echo $category->term_id; ?>" <?php echo in_array($category->term_id, json_decode($film['category_ids'])) ? 'checked' : ''; ?> /> <?php echo $category->cat_name; ?>
                          </div>
                        <?php } } ?>
                      </div>
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
        <div class="modal-content">
          <div style="overflow-x:auto;">
            <table class="wp-list-table widefat striped table-view-list">
              <thead>
                <tr>
                  <th>Tập phim</th>
                  <th>Video</th>
                  <th>Độ dài phim</th>
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
                    <td>
                      <p><?php echo $episode->_film_length; ?></p>
                    </td>
                    <td>
                      <form action="" method="POST">
                        <input hidden type="text" name="filmId" value="<?php echo $episode->ID; ?>" />
                        <input hidden type="text" class="video-url" name="_film_episode" value="<?php echo $episode->_film_episode; ?>" />
                        <input hidden type="text" class="video-length" name="_film_length" value="<?php echo $episode->_film_length; ?>" />
                        <button type="submit" class="button button-primary" name="updateEpisode">Cập nhật</button>
                      </form>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div id="modal-delete-film-<?php echo $film['id']; ?>" class="film-modal modal-delete d-none">
      <div class="modal-wrapper">
        <p onclick="hideModal('modal-delete-film-<?php echo $film['id']; ?>')" class="close">✕</p>
        <div class="modal-header">
          <p>Xóa phim</p>
        </div>
        <form action="" method="POST">
          <div class="modal-content">
            Bạn có muốn xóa phim <b><?php echo $film['film_name']; ?></b> và tất cả các tập phim?
          </div>
          <div class="modal-actions">
            <input hidden type="text" name="filmId" value="<?php echo $film['id']; ?>" />
            <button name="deleteFilm" class="button button-primary">Xóa</button>
            <button onclick="hideModal('modal-delete-film-<?php echo $film['id']; ?>')" type="button" class="button">Hủy</button>
          </div>
        </form>
      </div>
    </div>
  <?php } ?>
</div>
