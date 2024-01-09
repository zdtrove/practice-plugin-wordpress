<?php
  $users = $wpdb->get_results( 'SELECT * FROM wp_woo_rank ORDER BY id ASC', ARRAY_A );
  $currentPage = ! empty( $_GET['paged'] ) ? (int) $_GET['paged'] : 1;
  $total = count( $users );
  $perPage = 10;
  $totalPages = ceil($total/ $perPage);
  $currentPage = max($currentPage, 1);
  $currentPage = min($currentPage, $totalPages);
  $offset = ($currentPage - 1) * $perPage;
  if ($offset < 0) $offset = 0;
  $users = array_slice($users, $offset, $perPage);
?>

<table class="wp-list-table widefat fixed striped table-view-list users">
  <thead>
    <tr>
      <td class="manage-column column-cb check-column">
        <input type="checkbox" id="checkAllRank" />
      </td>
      <th>Hình ảnh</th>
      <th>Xếp hạng</th>
      <th>Chi tiêu tối thiểu</th>
      <th>Khuyến mãi</th>
      <th>Hành động</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ( $users as $key => $value ) { ?>
      <tr>
        <th class="check-column">
          <input type="checkbox" value="<?php echo $value['id']; ?>" name="checkRank" />
        </th>
        <td style="max-width: 50px;">
          <img src="<?php echo $value['imageurl'] ?>" alt="" width="50px"/>
        </td>
        <td><?php echo $value['name'] ?></td>
        <td><?php echo $value['minimum_spending'] ?></td>
        <td><?php echo $value['price_sale_off'] ?></td>
        <td class="table-actions">
          <span onclick="openEditModal(<?php echo $value['id']; ?>)" class="button dashicons dashicons-edit-page"></span>
          <span onclick="showModal('modal-delete-ranking-<?php echo $value['id']; ?>')" class="button delete">✕</span>
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>
<ul class="pagination">
  <?php
    if ( !empty( $_GET['paged'] ) ) $pg = $_GET['paged'];

    if ( isset( $pg ) && $pg > 1 ) {
      echo '<li><a class="button" href="'.site_url().'/wp-admin/admin.php?page=tich-diem&paged=' . ( $pg - 1 ) . '">«</a></li>';
    }

    for ( $i = 1; $i <= $totalPages; $i++ ) {
      if ( isset( $pg ) && $pg == $i )  $active = 'active';
      else $active = '';
      echo '<li><a href="'.site_url().'/wp-admin/admin.php?page=tich-diem&paged=' . $i . '" class="button ' . $active . '">' . $i . '</a></li>';
    }

    if ( isset( $pg ) && $pg < $totalPages ) {
      echo '<li><a class="button" href="'.site_url().'/wp-admin/admin.php?page=tich-diem&paged=' . ( $pg + 1 ). '">»</a></li>';
    }
  ?>
</ul>
<?php foreach ( $users as $key => $value ) { ?>
  <div id="modal-delete-ranking-<?php echo $value['id']; ?>" class="modal modal-delete d-none">
    <div class="modal-wrapper">
      <p onclick="hideModal('modal-delete-ranking-<?php echo $value['id']; ?>')" class="close">✕</p>
      <div class="modal-header">
        <p>Xóa xếp hạng</p>
      </div>
      <div class="modal-content" style="padding-bottom: 0">
        <p style="font-size: 20px; margin: unset">
          Bạn muốn xóa xếp hạng <i style="font-size: 30px;"><b><?php echo $value['name']; ?></b></i> ?
        </p>
        <form action="" method="POST">
          <div class="flex-center" style="justify-content: flex-end; margin-top: 20px;">
            <button onclick="hideModal('modal-delete-ranking-<?php echo $value['id']; ?>')" type="button" class="button">Không</button>
            <button type="submit" class="button delete" name="deleteRanking">Có</button>
          </div>
          <input hidden name="rankId" value="<?php echo $value['id']; ?>" />
        </form>
      </div>
    </div>
  </div>

  <div id="modal-edit-ranking-<?php echo $value['id']; ?>" class="modal d-none">
    <div class="modal-wrapper">
      <p onclick="hideModal('modal-edit-ranking-<?php echo $value['id']; ?>')" class="close">✕</p>
      <div class="modal-header">
        <p>Chỉnh sửa xếp hạng</p>
      </div>
      <form action="" method="POST">
        <div class="modal-content">
          <ul id="modal-steps-edit-<?php echo $value['id']; ?>" class="steps">
            <li id="step-1-edit-<?php echo $value['id']; ?>">
              <p class="step-number button">1</p>
              <span class="check dashicons dashicons-saved"></span>
              <p class="step-title">Thiết lập điểm</p>
            </li>
            <li id="step-2-edit-<?php echo $value['id']; ?>">
              <p class="step-number button">2</p>
              <span class="check dashicons dashicons-saved"></span>
              <p class="step-title">Thiết lập xếp hạng thành viên</p>
            </li>
            <li id="step-3-edit-<?php echo $value['id']; ?>">
              <p class="step-number button">3</p>
              <span class="check dashicons dashicons-saved"></span>
              <p class="step-title">Thiết lập khuyến mãi</p>
            </li>
            <li id="step-4-edit-<?php echo $value['id']; ?>">
              <p class="step-number button">4</p>
              <span class="check dashicons dashicons-saved"></span>
              <p class="step-title">Kiểm tra lại</p>
            </li>
          </ul>
          <div id="content-step-1-edit-<?php echo $value['id']; ?>" class="step-content">
            <div class="step-content-header">
              <h2>Thiết lập giá trị tích điểm</h2>
              <p>Xác định số điểm sẽ được cộng cho khách hàng khi hoàn thành đơn hàng và giá trị quy đổi điểm thành tiền khi thanh toán</p>
            </div>
            <hr />
            <div class="step-content-content flex-center">
              <div>
                <h4>Tỉ lệ tích điểm theo chỉ tiêu</h4>
                <div class="flex-center group-input">
                  <div>
                    <p class="required">Số tiền chi tiêu quy đổi ra 1 điểm</p>
                    <input type="text" class="regular-text">
                  </div>
                  <div>
                    <p>Điểm quy đổi ra tiền</p>
                    <input type="text">
                  </div>
                </div>
              </div>
              <div>
                <h4>Tỉ lệ tích điểm theo chỉ tiêu</h4>
                <div class="flex-center group-input">
                  <div>
                    <p>Số tiền chi tiêu</p>
                    <input type="text" class="regular-text">
                  </div>
                  <div>
                    <p class="required">Điểm quy đổi ra tiền</p>
                    <input type="text">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="content-step-2-edit-<?php echo $value['id']; ?>" class="step-content">
            <div class="step-content-header">
              <h2>Thiết lập xếp hạng thành viên</h2>
              <p>Tạo mới hạng thành viên và điều kiện đạt hạng</p>
            </div>
            <hr />
            <div class="step-content-content" style="overflow-x:auto;">
              <table class="wp-list-table widefat striped table-view-list">
                <thead>
                  <tr>
                    <th>Hình ảnh</th>
                    <th>Xếp hạng</th>
                    <th>Chi tiêu tối thiểu</th>
                    <th>Hành động</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="flex-center">
                      <button id="upload-image-button-edit-<?php echo $value['id']; ?>" class="button flex-center">
                        <span class="dashicons dashicons-admin-media"></span>
                        <span>Đổi hình</span>
                      </button>
                      <input type="text" id="image-url-edit-<?php echo $value['id']; ?>" hidden name="imageurl" value="<?php echo $value['imageurl']; ?>" />
                      <div class="image-wrapper" id="image-wrapper-edit-<?php echo $value['id']; ?>" style="width: 50px; height: auto;">
                        <?php
                          if ($value['imageurl']) {
                            echo '<img src="' . $value['imageurl'] . '" alt="" style="width: 50px; height: auto;" />';
                          }
                        ?>
                      </div>
                    </td>
                    <td>
                      <p class="required">Rank</p>
                      <input class="rank-name" type="text" name="name" placeholder="Vui lòng nhập Rank" value="<?php echo $value['name']; ?>" />
                    </td>
                    <td>
                      <p class="required">Chi tiêu tối thiểu</p>
                      <input type="number" name="minimum_spending" placeholder="Vui lòng nhập Chi tiêu tối thiểu" value="<?php echo $value['minimum_spending']; ?>" />
                    </td>
                    <td>
                      <span disabled class="button delete">✕</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div id="content-step-3-edit-<?php echo $value['id']; ?>" class="step-content">
            <div class="step-content-header">
              <h2>Thiết lập ưu đãi hạng thành viên</h2>
              <p>Tạo ưu đãi thành viên để hấp dẫn khách hàng mua hàng</p>
            </div>
            <hr />
            <div class="step-content-content" style="overflow-x:auto;">
              <table class="wp-list-table widefat striped table-view-list">
                  <thead>
                    <tr>
                      <th>Xếp hạng</th>
                      <th>Khuyến mãi</th>
                      <th>Giới hạn khuyến mãi</th>
                      <th>Số tiền khuyến mãi tối đa cho một đơn hàng</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <span class="dashicons dashicons-plus-alt"></span>
                        <span class="show-rank"><?php echo $value['name']; ?></span>
                      </td>
                      <td>
                        <input type="number" value="<?php echo $value['price_sale_off']; ?>" name="price_sale_off" placeholder="Vui lòng nhập Khuyến mãi" />
                      </td>
                      <td>
                        <input type="checkbox"  class="is-limit-input" name="is_limit" <?php echo ($value['is_limit'] == 1 ? 'checked' : '');?> />
                      </td>
                      <td>
                        <p class="<?php echo ($value['is_limit'] == 1 ? 'd-none' : ''); ?>">Không giới hạn số tiền</p>
                        <div class="is-limit-content <?php echo ($value['is_limit'] == 1 ? '' : 'd-none'); ?>">
                          <p class="required">Số tiền khuyến mãi tối đa cho một đơn hàng</p>
                          <input
                            class="price-sale-off-max"
                            type="number"
                            name="price_sale_off_max"
                            placeholder="Vui lòng nhập Số tiền khuyến mãi tối đa cho một đơn hàng"
                            value="<?php echo $value['price_sale_off_max']; ?>"
                          />
                        </div>
                      </td>
                    </tr>
                  </tbody>
              </table>
            </div>
          </div>
          <div id="content-step-4-edit-<?php echo $value['id']; ?>" class="step-content">
            <div class="step-content-header">
              <h2>Hãy kiểm tra lại thông tin trước khi bấm tạo nhé!</h2>
              <p>Quy tắc tích điểm: <b>Chi tiêu 10,000đ = 1 Điểm</b></p>
              <p>Quy tắc đổi điểm: <b>1 điểm = 3,000đ</b></p>
            </div>
            <hr />
            <div class="step-content-content" style="overflow-x:auto;">
              <table class="wp-list-table widefat striped table-view-list">
                  <thead>
                    <tr>
                      <th>Xếp hạng</th>
                      <th>Chi tiêu tối thiểu</th>
                      <th>Khuyến mãi</th>
                      <th>Số tiền khuyến mãi tối đa cho một đơn hàng</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <span class="dashicons dashicons-plus-alt"></span>
                        <span class="show-rank-final"><?php echo $value['name']; ?></span>
                      </td>
                      <td>
                        <span class="show-minimum-spending-final"><?php echo $value['minimum_spending']; ?></span>
                      </td>
                      <td>
                        <span class="show-price-sale-off-final"><?php echo $value['price_sale_off']; ?></span>
                      </td>
                      <td>
                        <span>Không giới hạn số tiền</span>
                        <div class="is-limit-content-final d-none">
                          <span class="show-price-sale-off-max-final"><?php echo $value['price_sale_off_max']; ?></span>
                        </div>
                      </td>
                    </tr>
                  </tbody>
              </table>
            </div>
          </div>
        </div>
        <input type="text" name="rankId" hidden value="<?php echo $value['id']; ?>" />
        <div class="modal-actions">
          <button type="button" id="modal-prev-edit-<?php echo $value['id']; ?>" class="button">Quay lại</button>
          <button type="button" id="modal-next-edit-<?php echo $value['id']; ?>" class="button button-primary">Tiếp theo</button>
          <button type="submit" id="modal-update-edit-<?php echo $value['id']; ?>" class="button button-primary" name="editRanking">Cập nhật</button>
        </div>
      </form>
    </div>
  </div>
<?php } ?>