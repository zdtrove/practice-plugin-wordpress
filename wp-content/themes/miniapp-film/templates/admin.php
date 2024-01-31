<?php
  global $wpdb;
  $tableFilms = $wpdb->prefix . 'films';

  if (isset($_POST['addFilm'])) {
    $arrayInsert = array(
      'category_id' => $_POST['category_id'],
      'film_name' => $_POST['film_name'],
      'film_poster' => $_POST['film_poster'],
    );

    $wpdb->insert($tableFilms, $arrayInsert);
  }

  $films = $wpdb->get_results( 'SELECT * FROM ' . $tableFilms . ' ORDER BY id ASC', ARRAY_A );
  $categories = get_categories(array('hide_empty' => 0 ));

  function getCategoryName($categories, $id) {
    foreach ($categories as $category) {
      if ($category->term_id == $id) {
        return $category->cat_name;
      }
    }
  }
?>

<div class="wrap">
  <h1>Danh sách phim</h1>
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
          <td><?php echo getCategoryName($categories, $film['category_id']) ?></td>
          <td>
            <button class="button">Chỉnh sửa</button>
            <button class="button">Thêm video phim</button>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
