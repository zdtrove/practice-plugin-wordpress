<div id="modal-add-film" class="film-modal modal-add-film d-none">
  <div class="modal-wrapper">
    <p onclick="hideModal('modal-add-film')" class="close">✕</p>
    <div class="modal-header">
      <p>Thêm phim</p>
    </div>
    <form id="formAddFilm" action="" method="POST">
      <div class="modal-content">
        <table class="form-table">
          <tr>
            <th>
              <label>Tên phim</label>
            </th>
            <td>
              <input name="film_name" type="text" class="regular-text require-field">
              <p class="required d-none">Đây là trường bắt buộc</p>
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
              <div style="display: flex; justify-content: flex-start; flex-wrap: wrap; gap: 15px;">
                <?php foreach ($categories as $category) {
                  if ($category->cat_name != 'Uncategorized') {
                ?>
                <div>
                    <input type="checkbox" name="category_ids[]" value="<?php echo $category->term_id; ?>" /> <?php echo $category->cat_name; ?>
                  </div>
                <?php } } ?>
              </div>
            </td>
          </tr>
          <tr>
            <th>Phần</th>
            <td>
              <input type="number" name="film_season" min="0" oninput="this.value = Math.abs(this.value)" />
            </td>
          </tr>
          <tr>
            <th>Chiếc khấu</th>
            <td>
              <input type="number" name="discount" min="0" oninput="this.value = Math.abs(this.value)" />
            </td>
          </tr>
          <tr>
            <th>Chọn phim cha</th>
            <td>
              <select name="film_parent">
                <option value="" selected>Chọn phim cha</option>
                <?php
                  foreach ($films as $film) { ?>
                    <option value="<?php echo $film['id']; ?>"><?php echo $film['film_name']; ?><?php echo !empty($film['film_season']) ? ' - Phần ' . $film['film_season'] : ''; ?></option>
                  <?php }
                ?>
              </select>
            </td>
          </tr>
        </table>
      </div>
      <div class="modal-actions">
        <input hidden name="addFilm" />
        <button onclick="submitForm('formAddFilm')" type="button" class="button button-primary">
          <span>Thêm phim</span>
        </button>       
      </div>
    </form>
  </div>
</div>
