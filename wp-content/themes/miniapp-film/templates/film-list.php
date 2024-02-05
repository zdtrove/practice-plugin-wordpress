<div>
  <button class="button button-primary button-add-film">
    <span>Thêm phim</span>
  </button>
</div>
<br />
<table class="wp-list-table widefat fixed striped table-view-list">
  <thead>
    <tr>
      <th>Tên phim</th>
      <th>Poster phim</th>
      <th>Chiết khấu</th>
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
        <td>
          <p>Chiết khấu</p>
          <p><?php echo $film['discount']; ?></p>
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
