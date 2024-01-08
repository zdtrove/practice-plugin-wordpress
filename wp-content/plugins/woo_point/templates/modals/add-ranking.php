<div id="modal-add" class="modal d-none">
  <div class="modal-wrapper">
    <p id="modal-close" class="close">✕</p>
    <div class="modal-header">
      <p>Chỉnh sửa</p>
    </div>
    <form action="" method="POST">
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
                    <button id="aw_upload_image_button" class="aw_upload_image_button button flex-center">
                      <span class="dashicons dashicons-admin-media"></span>
                      <span>Tải lên</span>
                    </button>
                    <input type="text" id="aw_custom_image" hidden name="imageurl" />
                    <div id="image-wrapper" style="width: 50px; height: auto;"></div>
                  </td>
                  <td>
                    <p class="required">Rank</p>
                    <input class="rank-name" type="text" name="name" placeholder="Vui lòng nhập Rank" />
                  </td>
                  <td>
                    <p class="required">Chi tiêu tối thiểu</p>
                    <input type="number" name="minimum_spending" placeholder="Vui lòng nhập Chi tiêu tối thiểu" />
                  </td>
                  <td>
                    <span class="button delete">✕</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div id="content-step-3" class="step-content">
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
                      <span class="show-rank"></span>
                    </td>
                    <td>
                      <input type="number" name="price_sale_off" placeholder="Vui lòng nhập Khuyến mãi" />
                    </td>
                    <td>
                      <input type="checkbox" class="is-limit-input" name="is_limit" />
                    </td>
                    <td class="is-limit-td">
                      <p>Không giới hạn số tiền</p>
                      <div class="is-limit-content d-none">
                        <p class="required">Số tiền khuyến mãi tối đa cho một đơn hàng</p>
                        <input type="number" name="price_sale_off_max" placeholder="Vui lòng nhập Số tiền khuyến mãi tối đa cho một đơn hàng" />
                      </div>
                    </td>
                  </tr>
                </tbody>
            </table>
          </div>
        </div>
        <div id="content-step-4" class="step-content">
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
                    <th>Giới hạn khuyến mãi</th>
                    <th>Số tiền khuyến mãi tối đa cho một đơn hàng</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <span class="dashicons dashicons-plus-alt"></span>
                      <span class="show-rank">Chưa làm, cứ submit vẫn tạo được record</span>
                    </td>
                    <td>
                      Chưa làm, cứ submit vẫn tạo được record
                    </td>
                    <td>
                      Chưa làm, cứ submit vẫn tạo được record
                    </td>
                    <td>
                      Chưa làm, cứ submit vẫn tạo được record
                    </td>
                    <td class="is-limit-td">
                      Chưa làm, cứ submit vẫn tạo được record
                    </td>
                  </tr>
                </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-actions">
        <button type="button" id="modal-prev" class="button">Quay lại</button>
        <button type="button" id="modal-next" class="button button-primary">Tiếp theo</button>
        <button type="submit" id="modal-update" class="button button-primary" name="add_ranking">Cập nhật</button>
      </div>
    </form>
  </div>
</div>