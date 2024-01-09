<?php
  global $wpdb;
  $successMessage = '';
  $errorMessage = '';
  $table = $wpdb->prefix . 'woo_rank';

  if (isset($_POST['addRanking'])) {
    $arrayInsert = array(
      'imageurl' => $_POST['imageurl'],
      'name' => $_POST['name'],
      'minimum_spending' => $_POST['minimum_spending'],
      'price_sale_off' => $_POST['price_sale_off'],
      'price_sale_off_max' => $_POST['price_sale_off_max'],
    );

    if (isset($_POST['is_limit'])) {
      $arrayInsert = array_merge($arrayInsert, array('is_limit' => $_POST['is_limit'] ? 1 : 0));
    }

    $add = $wpdb->insert($table, $arrayInsert);

    if ($add) {
      $successMessage = 'Thêm hạng thành viên thành công';
    }
  }

  if (isset($_POST['deleteRanking'])) {
    $delete = $wpdb->delete($table, array('id' => $_POST['rankId']));

    if ($delete) {
      $successMessage = 'Xóa hạng thành viên thành công';
    }
  }

  if (isset($_POST['editRanking'])) {
    $arrayUpdate = array(
      'imageurl' => $_POST['imageurl'],
      'name' => $_POST['name'],
      'minimum_spending' => $_POST['minimum_spending'],
      'price_sale_off' => $_POST['price_sale_off'],
      'price_sale_off_max' => $_POST['price_sale_off_max'],
    );

    if (isset($_POST['is_limit'])) {
      $arrayUpdate = array_merge($arrayUpdate, array('is_limit' => $_POST['is_limit'] ? 1 : 0));
    } else {
      $arrayUpdate = array_merge($arrayUpdate, array('is_limit' => 0));
    }

    $update = $wpdb->update($table, $arrayUpdate, array('id' => $_POST['rankId']));

    if ($update) {
      $successMessage = 'Chỉnh sửa hạng thành viên thành công';
    }
  }
?>

<div class="wrap">
  <h1>Tích điểm</h1>
  <br />
  <?php if ($successMessage) { ?>
    <div id="message" class="success-message">
      <p><?php echo $successMessage; ?></p>
      <button id="remove-message" onclick="removeMessage()" type="button"></button>
    </div>
  <?php } ?>
  <div class="actions">
    <button id="modal-add-btn" class="button">
      <span class="dashicons dashicons-plus-alt2"></span>
      <span>Thêm</span>
    </button>
    <button id="modal-edit-btn" class="button">
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
      <li class="active">
        <a href="#tab-1">Danh sách xếp hạng</a>
      </li>
      <li>
        <a href="#tab-2">Danh sách thành viên</a>
      </li>
    </ul>
    <div class="tab-content">
      <div id="tab-1" class="tab-pane active">
        <?php require_once(dirname(__FILE__) . '/ranking-list.php'); ?>
      </div>
      <div id="tab-2" class="tab-pane">
        <h3>Danh sách thành viên</h3>
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
      <div class="modal-content" style="padding-bottom: 0">
        <p style="font-size: 20px; margin: unset">
          Bạn muốn xóa những xếp hạng này: ?
        </p>
        <form action="" method="POST">
          <div class="flex-center" style="justify-content: flex-end; margin-top: 20px;">
            <button type="button" class="button">Không</button>
            <button type="button" class="button delete" name="deleteAllRanking">Có</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
