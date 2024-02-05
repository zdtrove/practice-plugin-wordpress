<div id="modal-add-film" class="film-modal modal-add-film d-none">
  <div class="modal-wrapper">
    <p onclick="hideModal('modal-add-film')" class="close">✕</p>
    <div class="modal-header">
      <p>Thêm phim</p>
    </div>
    <form action="" method="POST">
      <div class="modal-content">
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
                <?php }
                } ?>
              </select>
            </td>
          </tr>
          <tr>
            <th>Chiếc khấu</th>
            <td>
              <input type="number" name="discount" />
            </td>
          </tr>
        </table>
      </div>
      <div class="modal-actions">
        <button name="addFilm" class="button button-primary">
          <span>Thêm phim</span>
        </button>       
      </div>
    </form>
  </div>
</div>
