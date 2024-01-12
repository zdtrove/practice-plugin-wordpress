<?php
  global $wpdb;
  $successMessage = '';
  $errorMessage = '';
  $table = $wpdb->prefix . 'woo_rank';
  $tableSetting = $wpdb->prefix . 'woo_setting';

  if (isset($_POST['addRanking'])) {
    for ($i = 0; $i < count($_POST['name']); $i++) {
      $arrayInsert = array(
        'imageurl' => $_POST['imageurl'][$i],
        'name' => $_POST['name'][$i],
        'minimum_spending' => $_POST['minimum_spending'][$i],
        'price_sale_off' => $_POST['price_sale_off'][$i],
        'price_sale_off_max' => $_POST['price_sale_off_max'][$i],
      );
  
      if (isset($_POST['is_limit'][$i])) {
        $arrayInsert = array_merge($arrayInsert, array('is_limit' => $_POST['is_limit'][$i] ? 1 : 0));
      }
  
      $wpdb->insert($table, $arrayInsert);
    }

    $successMessage = 'Thêm hạng thành viên thành công';
  }

  if (isset($_POST['deleteRanking'])) {
    $delete = $wpdb->delete($table, array('id' => $_POST['rankId']));

    if ($delete) {
      $successMessage = 'Xóa hạng thành viên thành công';
    }
  }

  if (isset($_POST['editRanking'])) {
    for ($i = 0; $i < count($_POST['name']); $i++) {
      $arrayUpdate = array(
        'imageurl' => $_POST['imageurl'][$i],
        'name' => $_POST['name'][$i],
        'minimum_spending' => $_POST['minimum_spending'][$i],
        'price_sale_off' => $_POST['price_sale_off'][$i],
        'price_sale_off_max' => $_POST['price_sale_off_max'][$i],
      );

      if (isset($_POST['is_limit'][$i])) {
        $arrayUpdate = array_merge($arrayUpdate, array('is_limit' => $_POST['is_limit'][$i] ? 1 : 0));
      } else {
        $arrayUpdate = array_merge($arrayUpdate, array('is_limit' => 0));
      }

      $update = $wpdb->update($table, $arrayUpdate, array('id' => $_POST['rankId']));
    }
    $successMessage = 'Chỉnh sửa hạng thành viên thành công';
  }

  if (isset($_POST['editAllRanking'])) {
    for ($i = 0; $i < count($_POST['name']); $i++) {
      $arrayUpdate = array(
        'imageurl' => $_POST['imageurl'][$i],
        'name' => $_POST['name'][$i],
        'minimum_spending' => $_POST['minimum_spending'][$i],
        'price_sale_off' => $_POST['price_sale_off'][$i],
        'price_sale_off_max' => $_POST['price_sale_off_max'][$i],
      );

      if (isset($_POST['is_limit'][$i])) {
        $arrayUpdate = array_merge($arrayUpdate, array('is_limit' => $_POST['is_limit'][$i] ? 1 : 0));
      } else {
        $arrayUpdate = array_merge($arrayUpdate, array('is_limit' => 0));
      }

      $update = $wpdb->update($table, $arrayUpdate, array('id' => $_POST['rankId'][$i]));
    }

    $settings = $wpdb->get_results( 'SELECT * FROM ' . $tableSetting . ' ORDER BY id ASC', ARRAY_A );
    if (count($settings) === 0) {
      $wpdb->insert($tableSetting, array('points_converted_to_money' => $_POST['points_converted_to_money'], 'amount_spent' => $_POST['amount_spent']));
    } else {
      $wpdb->update($tableSetting, array('points_converted_to_money' => $_POST['points_converted_to_money'], 'amount_spent' => $_POST['amount_spent']), array('id' => $settings[0]['id']));
    }    
    
    $successMessage = 'Chỉnh sửa hạng thành viên thành công';
  }

  if (isset($_POST['deleteAllRanking'])) {
    foreach ($_POST['id-delete'] as $key => $value) {
      $wpdb->delete($table, array('id' => $value));
    }
    $successMessage = 'Xóa hạng thành viên thành công';
  }
?>

<div class="wrap">
  <h1>Tích điểm</h1>
  <br />
  <?php if ($successMessage) { ?>
    <div id="message" class="success-message">
      <p><?php echo $successMessage; ?></p>
      <button id="remove-message" type="button"></button>
    </div>
  <?php } ?>
  <div class="actions">
    <button id="button-open-modal-add" class="button">
      <span class="dashicons dashicons-plus-alt2"></span>
      <span>Thêm</span>
    </button>
    <button id="button-open-modal-edit-all" onclick="openEditAllModal()" class="button">
      <span class="dashicons dashicons-edit-page"></span>
      <span>Chỉnh sửa tất cả</span>
    </button>
    <div id="button-delete-wrapper" class="d-none">
      <button id="modal-delete-btn" class="button delete">
        <b>✕</b>
        <span>Xóa</span>
      </button>
    </div>
  </div>
  <form method="post">
    <ul class="nav-tabs">
      <li onclick="showEditAll()" class="active">
        <a href="#tab-1">Danh sách xếp hạng</a>
      </li>
      <li onclick="hideEditAll()">
        <a href="#tab-2">Danh sách thành viên</a>
      </li>
    </ul>
    <div class="tab-content">
      <div id="tab-1" class="tab-pane active">
        <?php require_once(dirname(__FILE__) . '/ranking-list.php'); ?>
      </div>
      <div id="tab-2" class="tab-pane">
        <h3>Danh sách thành viên</h3>
        <?php require_once(dirname(__FILE__) . '/user-list.php'); ?>
      </div>
    </div>
    <div id="overlay" class="overlay d-none"></div>
    <?php require_once(dirname(__FILE__) . '/modals/add-ranking.php'); ?>
  </form>
  <div id="modal-delete-all-ranking" class="modal modal-delete d-none">
    <div class="modal-wrapper">
      <p onclick="hideModal('modal-delete-all-ranking')" class="close">✕</p>
      <div class="modal-header">
        <p>Xóa xếp hạng</p>
      </div>
      <div class="modal-content">
        <form action="" method="POST">
          <p style="font-size: 20px; margin: unset">
            Bạn muốn xóa những xếp hạng dưới đây:
          </p>
          <div id="deleteList"></div>
          <div class="flex-center" style="justify-content: flex-end; margin-top: 20px;">
            <button onclick="hideModal('modal-delete-all-ranking')" type="button" class="button">Không</button>
            <button type="submit" class="button delete" name="deleteAllRanking">Có</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
