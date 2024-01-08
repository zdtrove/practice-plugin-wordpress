<?php
  global $wpdb;
  $message = '';
  if (isset($_POST['add_ranking'])) {
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

    $result = $wpdb->insert($wpdb->prefix . 'woo_rank', $arrayInsert);

    if ($result) {
      $message = 'Thêm hạng thành viên thành công';
    }
  }
?>

<div class="wrap">
  <h1>Tích điểm</h1>
  <br />
  <?php if ($message) { ?>
    <div id="message" class="success-message">
      <p><?php echo $message; ?></p>
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
</div>
