<div class="wrap">
  <h1>Tích điểm</h1>
  <br />
  <div class="actions">
    <button id="modal-add-btn" class="button">
      <span class="dashicons dashicons-plus-alt2"></span>
      <span>Thêm</span>
    </button>
    <button id="modal-edit-btn" class="button">
      <span class="dashicons dashicons-edit-page"></span>
      <span>Chỉnh sửa tất cả</span>
    </button>
  </div>
  <ul class="nav-tabs">
    <li class="active">
      <a href="#tab-1">Danh sách xếp hạng</a>
    </li>
    <li>
      <a href="#tab-2">Danh sách thành viên</a>
    </li>
  </ul>
  <div class="tab-content">
    <div id="tab-1" class="tab-pane active">
      <form method="post">
        <?php
          global $wpdb;
          $users = $wpdb->get_results( 'SELECT * FROM wp_users ORDER BY id ASC', ARRAY_A );

          if (count($users) < 10) {
            for ($i = 1; $i < 8; $i++) {
              if (count($users) > 50) {
                break;
              }

              $users = [ ...$users, ...$users ];
            }
          }

          $currentPage = ! empty( $_GET['paged'] ) ? (int) $_GET['paged'] : 1;
          $total = count( $users );
          $perPage = 10;
          $totalPages = ceil($total/ $perPage);
          $currentPage = max($currentPage, 1);
          $currentPage = min($currentPage, $totalPages);
          $offset = ($currentPage - 1) * $perPage;
          if ($offset < 0) $offset = 0;
          $users = array_slice($users, $offset, $perPage);

          echo '<table class="wp-list-table widefat fixed striped table-view-list users">';
            echo '<thead>';
              echo '<tr>';
                echo '<td class="manage-column column-cb check-column">';
                  echo '<input type="checkbox" />';
                echo '</td>';
                echo '<th>Tên</th>';
                echo '<th>Email</th>';
                echo '<th>Tên hiển thị</th>';
                echo '<th>Ngày tháng</th>';
                echo '<th>Hành động</th>';
              echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
              foreach ( $users as $key => $value ) {
                echo '<tr>';
                  echo '<th class="check-column">';
                    echo '<input type="checkbox" />';
                  echo '</th>';
                  echo '<td>' . $value['user_login'] . '</td>';
                  echo '<td>' . $value['user_email'] . '</td>';
                  echo '<td>' . $value['user_nicename'] . '</td>';
                  echo '<td>' . $value['user_registered'] . '</td>';
                  echo '<td class="table-actions">';
                    echo '<span class="button dashicons dashicons-edit-page"></span>';
                    echo '<span class="button delete">✕</span>';
                  echo '</td>';
                echo '</tr>';
              }
            echo '</tbody>';
          echo '</table>';
          echo '<center>';
            echo '<ul class="pagination">';
              if ( !empty( $_GET['paged'] ) ) $pg = $_GET['paged'];
              if ( isset( $pg ) && $pg > 1 ) {
                echo '<li><a class="button" href="'.site_url().'/wp-admin/admin.php?page=antnh_plugin&paged=' . ( $pg - 1 ) . '">«</a></li>';
              }
                for ( $i = 1; $i <= $totalPages; $i++ ) {
                  if ( isset( $pg ) && $pg == $i )  $active = 'active';
                  else $active = '';
                  echo '<li><a href="'.site_url().'/wp-admin/admin.php?page=antnh_plugin&paged=' . $i . '" class="button ' . $active . '">' . $i . '</a></li>';
                }
              if ( isset( $pg ) && $pg < $totalPages ) echo '<li><a class="button" href="'.site_url().'/wp-admin/admin.php?page=antnh_plugin&paged=' . ( $pg + 1 ). '">»</a></li>';
            echo '</ul>';
          echo '</center>';
        ?>
      </form>
    </div>
    <div id="tab-2" class="tab-pane">
      <h3>Danh sách thành viên</h3>
    </div>
  </div>
  <div id="overlay" class="overlay d-none"></div>
  <div id="modal-add" class="modal d-none">
    <div class="modal-wrapper">
      <p id="modal-close" class="close">✕</p>
      <div class="modal-header">
        <p>Chỉnh sửa</p>
      </div>
      <div class="modal-content">
        <ul id="modal-steps" class="steps">
          <li id="step-1">
            <p class="step-number button">1</p>
            <span class="check dashicons dashicons-saved"></span>
            <p class="step-title">Thiết lập điểm</p>
          </li>
          <li id="step-2">
            <p class="step-number button">2</p>
            <span class="check dashicons dashicons-saved"></span>
            <p class="step-title">Thiết lập xếp hạng thành viên</p>
          </li>
          <li id="step-3">
            <p class="step-number button">3</p>
            <span class="check dashicons dashicons-saved"></span>
            <p class="step-title">Thiết lập khuyến mãi</p>
          </li>
          <li id="step-4">
            <p class="step-number button">4</p>
            <span class="check dashicons dashicons-saved"></span>
            <p class="step-title">Kiểm tra lại</p>
          </li>
        </ul>
        <div id="content-step-1" class="step-content">
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
        <div id="content-step-2" class="step-content">
          <div class="step-content-header">
            <h2>Thiết lập xếp hạng thành viên</h2>
            <p>Tạo mới hạng thành viên và điều kiện đạt hạng</p>
          </div>
          <hr />
        </div>
        <div id="content-step-3" class="step-content">
          <div class="step-content-header">
            <h2>Thiết lập ưu đãi hạng thành viên</h2>
            <p>Tạo ưu đãi thành viên để hấp dẫn khách hàng mua hàng</p>
          </div>
          <hr />
        </div>
        <div id="content-step-4" class="step-content">
          <div class="step-content-header">
            <h2>Hãy kiểm tra lại thông tin trước khi bấm tạo nhé!</h2>
            <p>Quy tắc tích điểm: <b>Chi tiêu 10,000đ = 1 Điểm</b></p>
            <p>Quy tắc đổi điểm: <b>1 điểm = 3,000đ</b></p>
          </div>
          <hr />
        </div>
      </div>
      <div class="modal-actions">
        <button id="modal-prev" class="button">Quay lại</button>
        <button id="modal-next" class="button button-primary">Tiếp theo</button>
        <button id="modal-update" class="button button-primary">Cập nhật</button>
      </div>
    </div>
  </div>
</div>
