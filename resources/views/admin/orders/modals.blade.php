<div class="modal fade" id="orderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-0" id="orderModalTitle">Thêm đơn hàng</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="orderForm" class="row g-3">
                    <input type="hidden" id="orderId">

                    <div class="col-12">
                        <div class="d-flex flex-wrap gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="customerMode" id="customerModeExisting" value="existing" checked>
                                <label class="form-check-label" for="customerModeExisting">Khách hàng</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="customerMode" id="customerModeGuest" value="guest">
                                <label class="form-check-label" for="customerModeGuest">Khách vãng lai</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6" id="customerSelectWrap">
                        <label class="form-label">Khách hàng</label>
                        <select id="customerSelect" class="form-select"></select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Thanh toán</label>
                        <select id="paymentMethod" class="form-select">
                            <option value="COD">COD</option>
                            <option value="VNPAY">VNPAY</option>
                            <option value="CREDIT_CARD">Thẻ</option>
                            <option value="BANK_TRANSFER">Chuyển khoản</option>
                            <option value="EWALLET">Ví điện tử</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tên khách hàng</label>
                        <input type="text" id="customerName" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" id="customerEmail" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" id="customerPhone" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Trạng thái</label>
                        <select id="orderStatus" class="form-select">
                            <option value="pending">Chờ xử lý</option>
                            <option value="processing">Đang xử lý</option>
                            <option value="shipping">Đang giao</option>
                            <option value="completed">Hoàn thành</option>
                            <option value="cancelled">Đã hủy</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Địa chỉ giao hàng</label>
                        <textarea id="shippingAddress" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Ghi chú</label>
                        <textarea id="note" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="col-12">
                        <div class="border rounded-4 p-3 bg-light-subtle">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                                <div class="text-muted small">Tổng tạm tính: <span class="fw-semibold text-danger" id="itemsTotalLabel">0 đ</span></div>
                            </div>

                            <div class="row g-2 align-items-end mb-3">
                                <div class="col-lg-7">
                                    <label class="form-label">Tìm sản phẩm</label>
                                    <select id="productSelect" class="form-select"></select>
                                </div>
                                <div class="col-lg-3 col-md-8">
                                    <label class="form-label">Số lượng</label>
                                    <input type="number" id="productQuantity" class="form-control" min="1" value="1">
                                </div>
                                <div class="col-lg-2 col-md-4">
                                    <button type="button" class="btn btn-outline-primary w-100" id="addItemBtn">Thêm</button>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-sm item-table align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Sản phẩm</th>
                                            <th class="text-center" style="width: 110px;">SL</th>
                                            <th class="text-end" style="width: 140px;">Giá</th>
                                            <th class="text-end" style="width: 160px;">Thành tiền</th>
                                            <th class="text-center" style="width: 72px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="orderItemsBody">
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">Chưa có sản phẩm</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 ms-auto">
                        <label class="form-label">Tổng tiền</label>
                        <input type="text" id="totalAmount" class="form-control fw-semibold text-danger" readonly>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" id="saveOrderBtn">Lưu đơn hàng</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title mb-0">Đổi trạng thái</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="statusOrderId">
                <label class="form-label">Trạng thái mới</label>
                <select id="statusSelect" class="form-select">
                    <option value="pending">Chờ xử lý</option>
                    <option value="processing">Đang xử lý</option>
                    <option value="shipping">Đang giao</option>
                    <option value="completed">Hoàn thành</option>
                    <option value="cancelled">Đã hủy</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="saveStatusBtn">Cập nhật</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-0">Chi tiết đơn hàng</h5>
                    <small class="text-muted" id="detailMeta"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>