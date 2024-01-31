<?php
  global $wpdb;
  $tableFilms = $wpdb->prefix . 'films';

  if (isset($_POST['addFilm'])) {
    $arrayInsert = array(
      'category_id' => $_POST['category_id'],
      'film_name' => $_POST['film_name'],
    );

    $wpdb->insert($tableFilms, $arrayInsert);
  }

  $films = $wpdb->get_results( 'SELECT * FROM ' . $tableFilms . ' ORDER BY id ASC', ARRAY_A );
  $categories = get_categories(array('hide_empty' => 0 ));
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
        <th>Category</th>
        <th>Hành động</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($films as $film) { ?>
        <tr>
          <td><?php echo $film['film_name']; ?></td>
          <td><?php echo $film['category_id']; ?></td>
          <td>
            <button class="button">Chỉnh sửa</button>
            <button class="button">Thêm video phim</button>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
