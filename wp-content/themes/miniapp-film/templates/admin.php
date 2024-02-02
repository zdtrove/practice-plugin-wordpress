<?php
  global $wpdb;
  $tableFilms = $wpdb->prefix . 'films';

  $categories = get_categories(array('hide_empty' => 0));

  function getCategoryName($categories, $id) {
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
    );

    $wpdb->insert($tableFilms, $arrayInsert);
  }

  if (isset($_POST['editFilm'])) {
    $arrayUpdate = array(
      'category_id' => $_POST['category_id'],
      'category_name' => getCategoryName($categories, $_POST['category_id']),
      'film_name' => $_POST['film_name'],
      'film_poster' => $_POST['film_poster'],
    );

    $wpdb->update($tableFilms, $arrayUpdate, array('id' => $_POST['filmId']));
  }

  $films = $wpdb->get_results('SELECT * FROM ' . $tableFilms . ' ORDER BY id ASC', ARRAY_A);
?>

<div class="wrap">
  <h1>Quản lý phim</h1>
  <hr />
  <form action="" method="POST">
    <table class="form-table">
      <tr>
        <th>
          <label>Tên phim</label>
        </th>
        <td>
          <input name="film_name" type="text" class="regular-text">
        </td>
      </tr>
      <tr id="tr-add-film">
        <th>
          <label>Poster phim</label>
        </th>
        <td>
          <button type="button" class="upload-poster-button button flex-center">
            <span class="dashicons dashicons-admin-media"></span>
            <span>Tải lên</span>
          </button>
          <input type="text" class="poster-url" hidden name="film_poster" />
          <div class="poster-wrapper" style="width: 150px; height: auto; margin-top: 10px;"></div>
        </td>
      </tr>
      <tr>
        <th>
          <label>Category</label>
        </th>
        <td>
        <select name="category_id">
          <?php foreach ($categories as $category) { 
              if ($category->cat_name != 'Uncategorized') {
            ?>
            <option value="<?php echo $category->term_id; ?>"><?php echo $category->cat_name; ?></option>
          <?php } } ?>
        </select>
        </td>
      </tr>
    </table>
    <br />
    <button name="addFilm" class="button button-primary">
      <span>Thêm phim</span>
    </button>
  </form>
  <br />
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
            <img src="<?php echo $film['film_poster'] ?>" alt="" width="50px"/>
          </td>
          <td><?php echo $film['category_name']; ?></td>
          <td style="display: flex; gap: 5px; flex-wrap: wrap;">
            <button onclick="openEditModal('<?php echo $film['id']; ?>')" class="button">Chỉnh sửa</button>
            <button class="button">Thêm video phim</button>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
  <div id="overlay" class="overlay d-none"></div>
  <?php foreach ( $films as $key => $film ) { ?>
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
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <p>Tên phim</p>
                      <input type="text" name="film_name" placeholder="Vui lòng nhập tên phim" value="<?php echo $film['film_name']; ?>" />
                    </td>
                    <td class="flex-center">
                      <button type="button" class="upload-poster-button button flex-center">
                        <span class="dashicons dashicons-admin-media"></span>
                        <span>Đổi hình</span>
                      </button>
                      <input class="poster-url" type="text" hidden name="film_poster" value="<?php echo $film['film_poster']; ?>" />
                      <div class="poster-wrapper" style="width: 50px; height: auto; margin-left: 10px;">
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
                        <?php } } ?>
                      </select>
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
  <?php } ?>
</div>
