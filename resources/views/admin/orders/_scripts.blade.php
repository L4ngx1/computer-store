<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ordersApiUrl = '/api/v1/admin/orders';
        const usersApiUrl = '/api/v1/admin/users';
        const productsApiUrl = '/api/v1/admin/products';

        const orderModalElement = document.getElementById('orderModal');
        const statusModalElement = document.getElementById('statusModal');
        const detailModalElement = document.getElementById('detailModal');

        const orderModal = new bootstrap.Modal(orderModalElement);
        const statusModal = new bootstrap.Modal(statusModalElement);
        const detailModal = new bootstrap.Modal(detailModalElement);

        const ordersTableBody = document.getElementById('ordersTableBody');
        const ordersPagination = document.getElementById('ordersPagination');
        const ordersSummary = document.getElementById('ordersSummary');
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const perPageSelect = document.getElementById('perPageSelect');
        const refreshBtn = document.getElementById('refreshBtn');
        const createOrderBtn = document.getElementById('createOrderBtn');

        const orderIdInput = document.getElementById('orderId');
        const orderModalTitle = document.getElementById('orderModalTitle');
        const customerModeExisting = document.getElementById('customerModeExisting');
        const customerModeGuest = document.getElementById('customerModeGuest');
        const customerSelectWrap = document.getElementById('customerSelectWrap');
        const customerSelect = document.getElementById('customerSelect');
        const customerNameInput = document.getElementById('customerName');
        const customerEmailInput = document.getElementById('customerEmail');
        const customerPhoneInput = document.getElementById('customerPhone');
        const paymentMethodInput = document.getElementById('paymentMethod');
        const orderStatusInput = document.getElementById('orderStatus');
        const shippingAddressInput = document.getElementById('shippingAddress');
        const noteInput = document.getElementById('note');
        const productSelect = document.getElementById('productSelect');
        const productQuantityInput = document.getElementById('productQuantity');
        const addItemBtn = document.getElementById('addItemBtn');
        const orderItemsBody = document.getElementById('orderItemsBody');
        const totalAmountInput = document.getElementById('totalAmount');
        const itemsTotalLabel = document.getElementById('itemsTotalLabel');
        const saveOrderBtn = document.getElementById('saveOrderBtn');
        const statusOrderIdInput = document.getElementById('statusOrderId');
        const statusSelect = document.getElementById('statusSelect');
        const saveStatusBtn = document.getElementById('saveStatusBtn');
        const detailMeta = document.getElementById('detailMeta');
        const detailBody = document.getElementById('detailBody');

        const state = {
            page: 1,
            perPage: 10,
            status: '',
            search: '',
            meta: {
                current_page: 1,
                last_page: 1,
                per_page: 10,
                total: 0,
            },
            orders: [],
            orderItems: [],
            customerOptions: [],
            productOptions: [],
        };

        let customerSelectInstance = null;
        let productSelectInstance = null;

        const statusLabels = {
            pending: { text: 'Chờ xử lý', className: 'bg-secondary' },
            processing: { text: 'Đang xử lý', className: 'bg-primary' },
            shipping: { text: 'Đang giao', className: 'bg-info text-dark' },
            completed: { text: 'Hoàn thành', className: 'bg-success' },
            cancelled: { text: 'Đã hủy', className: 'bg-danger' },
        };

        function formatMoney(value) {
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND',
                maximumFractionDigits: 0,
            }).format(Number(value || 0));
        }

        function formatDate(value) {
            if (!value) {
                return '-';
            }

            return new Date(value).toLocaleString('vi-VN');
        }

        function escapeHtml(value) {
            return String(value ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }

        function badgeForStatus(status) {
            const info = statusLabels[status] || { text: status || 'N/A', className: 'bg-dark' };
            return `<span class="badge ${info.className}">${info.text}</span>`;
        }

        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        }

        async function fetchJson(url, options = {}) {
            const response = await fetch(url, {
                credentials: 'same-origin',
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'X-Requested-With': 'XMLHttpRequest',
                    ...(options.headers || {}),
                },
                ...options,
            });

            const payload = await response.json().catch(() => ({}));

            if (!response.ok) {
                throw new Error(payload?.message || 'Có lỗi xảy ra.');
            }

            return payload;
        }

        function renderOrdersLoading() {
            ordersTableBody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status" aria-hidden="true"></div>
                    </td>
                </tr>
            `;
        }

        function renderEmptyOrders() {
            ordersTableBody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">Chưa có đơn hàng nào.</td>
                </tr>
            `;
        }

        function renderOrders() {
            if (!state.orders.length) {
                renderEmptyOrders();
                ordersSummary.textContent = 'Không có kết quả phù hợp.';
                ordersPagination.innerHTML = '';
                return;
            }

            ordersTableBody.innerHTML = state.orders.map((order) => {
                const customerName = order.user?.name || order.customer_name || 'Khách hàng';
                const contact = [order.user?.email || order.customer_email, order.user?.phone || order.customer_phone]
                    .filter(Boolean)
                    .join(' • ');
                const itemCount = order.items_count ?? order.items?.length ?? 0;

                return `
                    <tr>
                        <td class="fw-semibold">#${order.id}</td>
                        <td>
                            <div class="fw-semibold">${escapeHtml(customerName)}</div>
                            <div class="text-muted small">${escapeHtml(order.shipping_address || '')}</div>
                        </td>
                        <td>
                            <div>${escapeHtml(contact || '-')}</div>
                        </td>
                        <td>
                            <span class="fw-semibold">${itemCount}</span> sản phẩm
                        </td>
                        <td class="fw-semibold text-danger">${formatMoney(order.total_amount)}</td>
                        <td>${badgeForStatus(order.status)}</td>
                        <td class="text-muted small">${formatDate(order.created_at)}</td>
                        <td class="text-end">
                            <div class="btn-group" role="group" aria-label="Actions">
                                <button type="button" class="btn btn-sm btn-link text-secondary p-2" onclick="openDetailModal(${order.id})" title="Xem">
                                    <i class="bi bi-eye fs-6"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-link text-primary p-2" onclick="openEditModal(${order.id})" title="Sửa">
                                    <i class="bi bi-pencil-square fs-6"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-link text-info p-2" onclick="openStatusModal(${order.id})" title="Trạng thái">
                                    <i class="bi bi-arrow-repeat fs-6"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-link text-danger p-2" onclick="deleteOrder(${order.id})" title="Xóa">
                                    <i class="bi bi-trash fs-6"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');

            const start = (state.meta.current_page - 1) * state.meta.per_page + 1;
            const end = Math.min(start + state.orders.length - 1, state.meta.total);
            ordersSummary.textContent = `Hiển thị ${start}-${end} trên ${state.meta.total} đơn hàng`;
            renderPagination();
        }

        function renderPagination() {
            const { current_page: currentPage, last_page: lastPage } = state.meta;

            if (lastPage <= 1) {
                ordersPagination.innerHTML = '';
                return;
            }

            const items = [];
            items.push(`<li class="page-item ${currentPage === 1 ? 'disabled' : ''}"><button class="page-link" type="button" data-page="${currentPage - 1}">Trước</button></li>`);

            const start = Math.max(1, currentPage - 2);
            const end = Math.min(lastPage, currentPage + 2);

            if (start > 1) {
                items.push(`<li class="page-item"><button class="page-link" type="button" data-page="1">1</button></li>`);
                if (start > 2) {
                    items.push('<li class="page-item disabled"><span class="page-link">...</span></li>');
                }
            }

            for (let page = start; page <= end; page++) {
                items.push(`<li class="page-item ${page === currentPage ? 'active' : ''}"><button class="page-link" type="button" data-page="${page}">${page}</button></li>`);
            }

            if (end < lastPage) {
                if (end < lastPage - 1) {
                    items.push('<li class="page-item disabled"><span class="page-link">...</span></li>');
                }
                items.push(`<li class="page-item"><button class="page-link" type="button" data-page="${lastPage}">${lastPage}</button></li>`);
            }

            items.push(`<li class="page-item ${currentPage === lastPage ? 'disabled' : ''}"><button class="page-link" type="button" data-page="${currentPage + 1}">Sau</button></li>`);

            ordersPagination.innerHTML = items.join('');
            ordersPagination.querySelectorAll('button[data-page]').forEach((button) => {
                button.addEventListener('click', () => {
                    const nextPage = Number(button.dataset.page);
                    if (!Number.isFinite(nextPage) || nextPage < 1 || nextPage > lastPage || nextPage === currentPage) {
                        return;
                    }

                    loadOrders(nextPage);
                });
            });
        }

        async function loadOrders(page = state.page) {
            state.page = page;
            renderOrdersLoading();

            const params = new URLSearchParams({
                page: String(page),
                per_page: String(state.perPage),
            });

            if (state.status) {
                params.set('status', state.status);
            }

            if (state.search) {
                params.set('q', state.search);
            }

            const response = await fetchJson(`${ordersApiUrl}?${params.toString()}`);
            state.orders = response.data || [];
            state.meta = response.meta || state.meta;
            state.page = state.meta.current_page || page;
            renderOrders();
        }

        function customerToOption(customer) {
            const contact = [customer.email, customer.phone].filter(Boolean).join(' • ');

            return {
                value: String(customer.id),
                text: customer.name || `Khách hàng #${customer.id}`,
                search_text: [customer.name, customer.email, customer.phone].filter(Boolean).join(' '),
                subtitle: contact,
                address: customer.address || '',
                email: customer.email || '',
                phone: customer.phone || '',
                email_label: customer.email || '',
                phone_label: customer.phone || '',
            };
        }

        function productToOption(product) {
            const price = product.sale_price ?? product.price ?? 0;

            return {
                value: String(product.id),
                text: product.name || `Sản phẩm #${product.id}`,
                search_text: [product.name, product.sku].filter(Boolean).join(' '),
                subtitle: [product.sku || 'SKU', formatMoney(price)].join(' • '),
                price: Number(price),
                sku: product.sku || '',
                sku_label: product.sku || '',
                price_label: formatMoney(price),
            };
        }

        async function fetchCustomers(query = '') {
            const params = new URLSearchParams({
                role: 'customer',
                per_page: '20',
            });

            if (query) {
                params.set('q', query);
            }

            const response = await fetchJson(`${usersApiUrl}?${params.toString()}`);
            return response.data || [];
        }

        async function fetchProducts(query = '') {
            const params = new URLSearchParams({
                per_page: '20',
            });

            if (query) {
                params.set('q', query);
            }

            const response = await fetchJson(`${productsApiUrl}?${params.toString()}`);
            return response.data || [];
        }

        function initTomSelects() {
            if (!window.TomSelect) {
                console.warn('TomSelect is not available. Falling back to native selects.');
                return;
            }

            if (!customerSelectInstance) {
                customerSelectInstance = new TomSelect(customerSelect, {
                    valueField: 'value',
                    labelField: 'text',
                    searchField: ['search_text', 'text', 'email', 'phone'],
                    create: false,
                    maxOptions: 20,
                    preload: 'focus',
                    render: {
                        option(data, escape) {
                            const emailChip = data.email_label
                                ? `<span class="badge text-bg-light border text-dark fw-normal">${escape(data.email_label)}</span>`
                                : '';
                            const phoneChip = data.phone_label
                                ? `<span class="badge text-bg-light border text-dark fw-normal">${escape(data.phone_label)}</span>`
                                : '';
                            const contactChips = [emailChip, phoneChip].filter(Boolean).join(' ');

                            const skuChip = data.sku_label
                                ? `<span class="badge text-bg-light border text-dark fw-normal">SKU: ${escape(data.sku_label)}</span>`
                                : '';
                            const priceChip = data.price_label
                                ? `<span class="badge text-bg-light border text-dark fw-normal">${escape(data.price_label)}</span>`
                                : '';
                            const productChips = [skuChip, priceChip].filter(Boolean).join(' ');

                            return `
                                <div class="py-1">
                                    <div class="fw-semibold">${escape(data.text || '')}</div>
                                    ${data.email_label || data.phone_label
                                        ? `<div class="d-flex flex-wrap gap-1 mt-1">${contactChips}</div>`
                                        : ''}
                                    ${data.sku_label || data.price_label
                                        ? `<div class="d-flex flex-wrap gap-1 mt-1">${productChips}</div>`
                                        : ''}
                                </div>
                            `;
                        },
                        item(data, escape) {
                            return `<div>${escape(data.text || '')}</div>`;
                        },
                    },
                    load: async (query, callback) => {
                        try {
                            const customers = await fetchCustomers(query);
                            const options = customers.map(customerToOption);
                            state.customerOptions = customers;
                            callback(options);
                        } catch (error) {
                            console.warn('Unable to load customers:', error);
                            callback();
                        }
                    },
                    onChange() {
                        fillCustomerFieldsFromSelect();
                    },
                });
            }

            if (!productSelectInstance) {
                productSelectInstance = new TomSelect(productSelect, {
                    valueField: 'value',
                    labelField: 'text',
                    searchField: ['search_text', 'text', 'sku'],
                    create: false,
                    maxOptions: 20,
                    preload: 'focus',
                    render: {
                        option(data, escape) {
                            return `
                                <div>
                                    <div class="fw-semibold">${escape(data.text || '')}</div>
                                    <div class="text-muted small">${escape(data.subtitle || '')}</div>
                                </div>
                            `;
                        },
                        item(data, escape) {
                            return `<div>${escape(data.text || '')}</div>`;
                        },
                    },
                    load: async (query, callback) => {
                        try {
                            const products = await fetchProducts(query);
                            const options = products.map(productToOption);
                            state.productOptions = products;
                            callback(options);
                        } catch (error) {
                            console.warn('Unable to load products:', error);
                            callback();
                        }
                    },
                });
            }
        }

        function addCustomerOption(customer) {
            const option = customerToOption(customer);

            if (customerSelectInstance) {
                if (!customerSelectInstance.options[option.value]) {
                    customerSelectInstance.addOption(option);
                }
                customerSelectInstance.refreshOptions(false);
                customerSelectInstance.setValue(option.value, true);
                return;
            }

            state.customerOptions.push(customer);
            customerSelect.innerHTML = ['<option value="">-- Chọn khách hàng --</option>']
                .concat(state.customerOptions.map((item) => {
                    const contact = [item.email, item.phone].filter(Boolean).join(' • ');
                    return `<option value="${item.id}">${escapeHtml(item.name)}${contact ? ` - ${escapeHtml(contact)}` : ''}</option>`;
                }))
                .join('');
            customerSelect.value = String(customer.id);
        }

        function setCustomerValue(value) {
            if (customerSelectInstance) {
                customerSelectInstance.setValue(value || '', true);
                return;
            }

            customerSelect.value = value || '';
        }

        function setProductValue(value) {
            if (productSelectInstance) {
                productSelectInstance.setValue(value || '', true);
                return;
            }

            productSelect.value = value || '';
        }

        function clearCustomerValue() {
            setCustomerValue('');
        }

        function clearProductValue() {
            setProductValue('');
        }

        function refreshCustomerOptions() {
            if (customerSelectInstance) {
                customerSelectInstance.refreshOptions(false);
            }
        }

        function refreshProductOptions() {
            if (productSelectInstance) {
                productSelectInstance.refreshOptions(false);
            }
        }

        function syncCustomerMode() {
            const isGuest = customerModeGuest.checked;
            customerSelectWrap.classList.toggle('d-none', isGuest);
            customerNameInput.readOnly = !isGuest;
            customerEmailInput.readOnly = !isGuest;
            customerPhoneInput.readOnly = !isGuest;
            customerNameInput.required = isGuest;
            customerEmailInput.required = isGuest;
            customerPhoneInput.required = isGuest;

            if (isGuest) {
                clearCustomerValue();
            }
        }

        function fillCustomerFieldsFromSelect() {
            const selectedId = customerSelectInstance ? customerSelectInstance.getValue() : customerSelect.value;
            const customer = customerSelectInstance
                ? customerSelectInstance.options[selectedId]
                : state.customerOptions.find((item) => String(item.id) === String(selectedId));

            if (!customer) {
                return;
            }

            customerNameInput.value = customer.name || customer.text || '';
            customerEmailInput.value = customer.email || '';
            customerPhoneInput.value = customer.phone || '';
            if (customer.address) {
                shippingAddressInput.value = customer.address;
            }
        }

        function calculateItemsTotal() {
            return state.orderItems.reduce((sum, item) => sum + (Number(item.price) * Number(item.quantity)), 0);
        }

        function renderOrderItems() {
            if (!state.orderItems.length) {
                orderItemsBody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-4">Chưa có sản phẩm</td></tr>';
            } else {
                orderItemsBody.innerHTML = state.orderItems.map((item, index) => {
                    const subtotal = Number(item.price) * Number(item.quantity);
                    return `
                        <tr>
                            <td>
                                <div class="fw-semibold">${escapeHtml(item.product_name)}</div>
                                <div class="text-muted small">Mã sản phẩm: ${item.product_id}</div>
                            </td>
                            <td>
                                <input type="number" min="1" class="form-control form-control-sm" value="${item.quantity}" onchange="updateOrderItemQuantity(${index}, this.value)">
                            </td>
                            <td class="text-end">${formatMoney(item.price)}</td>
                            <td class="text-end fw-semibold">${formatMoney(subtotal)}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeOrderItem(${index})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                }).join('');
            }

            const total = calculateItemsTotal();
            totalAmountInput.value = formatMoney(total);
            itemsTotalLabel.textContent = formatMoney(total);
        }

        function resetOrderForm() {
            orderIdInput.value = '';
            orderModalTitle.textContent = 'Thêm đơn hàng';
            customerModeExisting.checked = true;
            customerModeGuest.checked = false;
            syncCustomerMode();
            clearCustomerValue();
            clearProductValue();
            customerNameInput.value = '';
            customerEmailInput.value = '';
            customerPhoneInput.value = '';
            paymentMethodInput.value = 'COD';
            orderStatusInput.value = 'pending';
            shippingAddressInput.value = '';
            noteInput.value = '';
            productQuantityInput.value = '1';
            state.orderItems = [];
            renderOrderItems();
        }

        function fillOrderForm(order) {
            orderIdInput.value = order.id;
            orderModalTitle.textContent = `Cập nhật đơn hàng #${order.id}`;

            if (order.user_id) {
                customerModeExisting.checked = true;
                customerModeGuest.checked = false;
                syncCustomerMode();
                if (order.user) {
                    addCustomerOption(order.user);
                } else {
                    setCustomerValue(String(order.user_id));
                }
                fillCustomerFieldsFromSelect();
            } else {
                customerModeGuest.checked = true;
                customerModeExisting.checked = false;
                syncCustomerMode();
                customerNameInput.value = order.customer_name || '';
                customerEmailInput.value = order.customer_email || '';
                customerPhoneInput.value = order.customer_phone || '';
            }

            paymentMethodInput.value = order.payment_method || 'COD';
            orderStatusInput.value = order.status || 'pending';
            shippingAddressInput.value = order.shipping_address || '';
            noteInput.value = order.note || '';
            state.orderItems = (order.items || []).map((item) => ({
                product_id: item.product_id,
                product_name: item.product?.name || item.product_name || 'Sản phẩm',
                quantity: Number(item.quantity || 1),
                price: Number(item.price || 0),
            }));
            renderOrderItems();
        }

        async function openCreateModal() {
            resetOrderForm();
            orderModal.show();
            window.setTimeout(() => {
                customerSelectInstance?.focus();
            }, 150);
        }

        async function openEditModal(orderId) {
            resetOrderForm();
            orderModal.show();

            const response = await fetchJson(`${ordersApiUrl}/${orderId}`);
            fillOrderForm(response.data);
        }

        async function openDetailModal(orderId) {
            const response = await fetchJson(`${ordersApiUrl}/${orderId}`);
            const order = response.data;
            const customerName = order.user?.name || order.customer_name || 'Khách hàng';
            detailMeta.textContent = `#${order.id} • ${customerName} • ${formatDate(order.created_at)}`;

            const items = order.items || [];
            const itemsHtml = items.length
                ? items.map((item) => `
                    <tr>
                        <td>${escapeHtml(item.product?.name || item.product_name || 'Sản phẩm')}</td>
                        <td class="text-center">${item.quantity}</td>
                        <td class="text-end">${formatMoney(item.price)}</td>
                        <td class="text-end">${formatMoney(Number(item.price) * Number(item.quantity))}</td>
                    </tr>
                `).join('')
                : '<tr><td colspan="4" class="text-center text-muted py-3">Không có sản phẩm</td></tr>';

            detailBody.innerHTML = `
                <div class="row g-3 mb-4">
                    <div class="col-lg-4">
                        <div class="border rounded-4 p-3 h-100 bg-light-subtle">
                            <div class="text-muted small mb-1">Khách hàng</div>
                            <div class="fw-semibold">${escapeHtml(customerName)}</div>
                            <div class="small text-muted mt-2">${escapeHtml(order.customer_email || order.user?.email || '-')}</div>
                            <div class="small text-muted">${escapeHtml(order.customer_phone || order.user?.phone || '-')}</div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="border rounded-4 p-3 h-100 bg-light-subtle">
                            <div class="text-muted small mb-1">Thông tin đơn</div>
                            <div class="mb-1">Trạng thái: ${badgeForStatus(order.status)}</div>
                            <div class="mb-1">Thanh toán: <span class="fw-semibold">${escapeHtml(order.payment_method || '-')}</span></div>
                            <div class="mb-1">Số sản phẩm: <span class="fw-semibold">${items.length}</span></div>
                            <div>Tổng tiền: <span class="fw-semibold text-danger">${formatMoney(order.total_amount)}</span></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="border rounded-4 p-3 h-100 bg-light-subtle">
                            <div class="text-muted small mb-1">Giao hàng</div>
                            <div class="fw-semibold">Địa chỉ</div>
                            <div class="small text-muted">${escapeHtml(order.shipping_address || '-')}</div>
                            <div class="fw-semibold mt-3">Ghi chú</div>
                            <div class="small text-muted">${escapeHtml(order.note || '-')}</div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Sản phẩm</th>
                                <th class="text-center">SL</th>
                                <th class="text-end">Giá</th>
                                <th class="text-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>${itemsHtml}</tbody>
                    </table>
                </div>
            `;

            detailModal.show();
        }

        function openStatusModal(orderId) {
            statusOrderIdInput.value = orderId;
            statusSelect.value = 'pending';
            statusModal.show();
        }

        async function saveOrder() {
            const isEdit = Boolean(orderIdInput.value);
            const orderItems = state.orderItems.map((item) => ({
                product_id: item.product_id,
                product_name: item.product_name,
                quantity: Number(item.quantity),
                price: Number(item.price),
            }));

            const payload = {
                user_id: customerModeGuest.checked ? null : ((customerSelectInstance ? customerSelectInstance.getValue() : customerSelect.value) || null),
                customer_name: customerNameInput.value.trim(),
                customer_email: customerEmailInput.value.trim(),
                customer_phone: customerPhoneInput.value.trim(),
                shipping_address: shippingAddressInput.value.trim(),
                note: noteInput.value.trim(),
                payment_method: paymentMethodInput.value,
                status: orderStatusInput.value,
                items: orderItems,
            };

            if (isEdit) {
                payload.total_amount = calculateItemsTotal();
            }

            const url = isEdit ? `${ordersApiUrl}/${orderIdInput.value}` : ordersApiUrl;
            const method = isEdit ? 'PATCH' : 'POST';

            await fetchJson(url, {
                method,
                body: JSON.stringify(payload),
            });

            orderModal.hide();
            await loadOrders(state.page);
        }

        async function saveStatus() {
            const orderId = statusOrderIdInput.value;
            await fetchJson(`${ordersApiUrl}/${orderId}/status`, {
                method: 'PATCH',
                body: JSON.stringify({ status: statusSelect.value }),
            });

            statusModal.hide();
            await loadOrders(state.page);
        }

        async function deleteOrder(orderId) {
            if (!confirm('Xóa đơn hàng này?')) {
                return;
            }

            await fetchJson(`${ordersApiUrl}/${orderId}`, {
                method: 'DELETE',
            });

            await loadOrders(state.page);
        }

        function addSelectedProduct() {
            const selectedId = productSelectInstance ? productSelectInstance.getValue() : productSelect.value;
            const selectedOption = productSelectInstance
                ? productSelectInstance.options[selectedId]
                : productSelect.options[productSelect.selectedIndex];

            if (!selectedOption || !selectedId) {
                alert('Vui lòng chọn sản phẩm.');
                return;
            }

            const productId = Number(selectedId);
            const quantity = Number(productQuantityInput.value || 1);
            if (quantity < 1) {
                alert('Số lượng phải lớn hơn 0.');
                return;
            }

            const productName = selectedOption.text || selectedOption.textContent?.trim() || '';
            const price = Number(selectedOption.price || selectedOption.dataset?.price || 0);
            const existingIndex = state.orderItems.findIndex((item) => Number(item.product_id) === productId);

            if (existingIndex >= 0) {
                state.orderItems[existingIndex].quantity += quantity;
            } else {
                state.orderItems.push({
                    product_id: productId,
                    product_name: productName,
                    quantity,
                    price,
                });
            }

            clearProductValue();
            productQuantityInput.value = '1';
            renderOrderItems();
        }

        window.updateOrderItemQuantity = function updateOrderItemQuantity(index, value) {
            const quantity = Number(value || 1);
            if (quantity < 1) {
                return;
            }

            state.orderItems[index].quantity = quantity;
            renderOrderItems();
        };

        window.removeOrderItem = function removeOrderItem(index) {
            state.orderItems.splice(index, 1);
            renderOrderItems();
        };

        window.openDetailModal = openDetailModal;
        window.openEditModal = openEditModal;
        window.openStatusModal = openStatusModal;
        window.deleteOrder = deleteOrder;

        initTomSelects();

        createOrderBtn.addEventListener('click', () => {
            openCreateModal();
        });

        refreshBtn.addEventListener('click', () => loadOrders(1));
        statusFilter.addEventListener('change', () => {
            state.status = statusFilter.value;
            loadOrders(1);
        });
        perPageSelect.addEventListener('change', () => {
            state.perPage = Number(perPageSelect.value || 10);
            loadOrders(1);
        });
        searchInput.addEventListener('input', () => {
            state.search = searchInput.value.trim();
            clearTimeout(window.__orderSearchTimer);
            window.__orderSearchTimer = setTimeout(() => loadOrders(1), 300);
        });
        customerModeExisting.addEventListener('change', syncCustomerMode);
        customerModeGuest.addEventListener('change', syncCustomerMode);
        addItemBtn.addEventListener('click', addSelectedProduct);
        saveOrderBtn.addEventListener('click', saveOrder);
        saveStatusBtn.addEventListener('click', saveStatus);

        loadOrders().catch((error) => {
            ordersTableBody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center text-danger py-5">${escapeHtml(error.message || 'Không tải được dữ liệu.')}</td>
                </tr>
            `;
        });
    });
</script>