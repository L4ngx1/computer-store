<script>
    document.addEventListener('DOMContentLoaded', () => {
        const api = {
            orders: '/api/admin/orders',
            users: '/api/admin/users',
            products: '/api/admin/products',
        };

        const $ = (id) => document.getElementById(id);
        const el = [
            'ordersTableBody', 'ordersPagination', 'ordersSummary', 'searchInput', 'statusFilter', 'perPageSelect',
            'refreshBtn', 'createOrderBtn', 'orderModal', 'statusModal', 'detailModal', 'orderId', 'orderModalTitle',
            'customerModeExisting', 'customerModeGuest', 'customerSelectWrap', 'customerSelect', 'customerName',
            'customerEmail', 'customerPhone', 'paymentMethod', 'orderStatus', 'shippingAddress', 'note',
            'productSelect', 'productQuantity', 'addItemBtn', 'orderItemsBody', 'totalAmount', 'itemsTotalLabel',
            'saveOrderBtn', 'statusOrderId', 'statusSelect', 'saveStatusBtn', 'detailMeta', 'detailBody',
        ].reduce((items, id) => ({ ...items, [id]: $(id) }), {});

        const modal = {
            order: new bootstrap.Modal(el.orderModal),
            status: new bootstrap.Modal(el.statusModal),
            detail: new bootstrap.Modal(el.detailModal),
        };

        const state = {
            page: 1,
            perPage: 10,
            status: '',
            search: '',
            orders: [],
            items: [],
            meta: { current_page: 1, last_page: 1, per_page: 10, total: 0 },
        };

        const statusMap = {
            pending: ['Chờ xử lý', 'bg-secondary'],
            processing: ['Đang xử lý', 'bg-primary'],
            shipping: ['Đang giao', 'bg-info text-dark'],
            completed: ['Hoàn thành', 'bg-success'],
            cancelled: ['Đã hủy', 'bg-danger'],
        };

        let customerSelect = null;
        let productSelect = null;
        let searchTimer = null;

        const money = (value) => new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND',
            maximumFractionDigits: 0,
        }).format(Number(value || 0));

        const date = (value) => value ? new Date(value).toLocaleString('vi-VN') : '-';
        const total = () => state.items.reduce((sum, item) => sum + Number(item.price) * Number(item.quantity), 0);
        const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content || '';
        const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (char) => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;',
        }[char]));

        function badge(status) {
            const [text, className] = statusMap[status] || [status || 'N/A', 'bg-dark'];
            return `<span class="badge ${className}">${text}</span>`;
        }

        function queryString(data) {
            const params = new URLSearchParams();
            Object.entries(data).forEach(([key, value]) => {
                if (value !== '' && value !== null && value !== undefined) {
                    params.set(key, value);
                }
            });
            return params.toString();
        }

        async function request(url, options = {}) {
            const response = await fetch(url, {
                credentials: 'same-origin',
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf(),
                    'X-Requested-With': 'XMLHttpRequest',
                    ...(options.headers || {}),
                },
                ...options,
            });
            const payload = await response.json().catch(() => ({}));

            if (!response.ok) {
                throw new Error(payload.message || 'Có lỗi xảy ra.');
            }

            return payload;
        }

        function renderLoading() {
            el.ordersTableBody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status" aria-hidden="true"></div>
                    </td>
                </tr>`;
        }

        function renderOrders() {
            if (!state.orders.length) {
                el.ordersTableBody.innerHTML = '<tr><td colspan="8" class="text-center text-muted py-5">Chưa có đơn hàng nào.</td></tr>';
                el.ordersSummary.textContent = 'Không có kết quả phù hợp.';
                el.ordersPagination.innerHTML = '';
                return;
            }

            el.ordersTableBody.innerHTML = state.orders.map((order) => {
                const customer = order.user?.name || order.customer_name || 'Khách hàng';
                const contact = [order.user?.email || order.customer_email, order.user?.phone || order.customer_phone].filter(Boolean).join(' • ');
                const itemCount = order.items_count ?? order.items?.length ?? 0;

                return `
                    <tr>
                        <td class="fw-semibold">#${order.id}</td>
                        <td>
                            <div class="fw-semibold">${escapeHtml(customer)}</div>
                            <div class="text-muted small">${escapeHtml(order.shipping_address || '')}</div>
                        </td>
                        <td>${escapeHtml(contact || '-')}</td>
                        <td><span class="fw-semibold">${itemCount}</span> sản phẩm</td>
                        <td class="fw-semibold text-danger">${money(order.total_amount)}</td>
                        <td>${badge(order.status)}</td>
                        <td class="text-muted small">${date(order.created_at)}</td>
                        <td class="text-end">
                            <div class="btn-group">
                                ${actionButton('detail', order.id, 'text-secondary', 'bi-eye', 'Xem')}
                                ${actionButton('edit', order.id, 'text-primary', 'bi-pencil-square', 'Sửa')}
                                ${actionButton('status', order.id, 'text-info', 'bi-arrow-repeat', 'Trạng thái')}
                                ${actionButton('delete', order.id, 'text-danger', 'bi-trash', 'Xóa')}
                            </div>
                        </td>
                    </tr>`;
            }).join('');

            const start = (state.meta.current_page - 1) * state.meta.per_page + 1;
            const end = Math.min(start + state.orders.length - 1, state.meta.total);
            el.ordersSummary.textContent = `Hiển thị ${start}-${end} trên ${state.meta.total} đơn hàng`;
            renderPagination();
        }

        function actionButton(action, id, color, icon, title) {
            return `
                <button type="button" class="btn btn-sm btn-link ${color} p-2" data-action="${action}" data-id="${id}" title="${title}">
                    <i class="bi ${icon} fs-6"></i>
                </button>`;
        }

        function renderPagination() {
            const current = state.meta.current_page;
            const last = state.meta.last_page;

            if (last <= 1) {
                el.ordersPagination.innerHTML = '';
                return;
            }

            const pages = [
                pageItem(current - 1, 'Trước', current === 1),
                ...Array.from({ length: last }, (_, index) => index + 1)
                    .filter((page) => page === 1 || page === last || Math.abs(page - current) <= 2)
                    .map((page, index, list) => `${index && page - list[index - 1] > 1 ? '<li class="page-item disabled"><span class="page-link">...</span></li>' : ''}${pageItem(page, page, false, page === current)}`),
                pageItem(current + 1, 'Sau', current === last),
            ];

            el.ordersPagination.innerHTML = pages.join('');
        }

        function pageItem(page, label, disabled = false, active = false) {
            return `<li class="page-item ${disabled ? 'disabled' : ''} ${active ? 'active' : ''}">
                <button class="page-link" type="button" data-page="${page}">${label}</button>
            </li>`;
        }

        async function loadOrders(page = state.page) {
            state.page = page;
            renderLoading();

            const response = await request(`${api.orders}?${queryString({
                page,
                per_page: state.perPage,
                status: state.status,
                q: state.search,
            })}`);

            state.orders = response.data || [];
            state.meta = response.meta || state.meta;
            state.page = state.meta.current_page || page;
            renderOrders();
        }

        function optionFromCustomer(customer) {
            return {
                value: String(customer.id),
                text: customer.name || `Khách hàng #${customer.id}`,
                searchText: [customer.name, customer.email, customer.phone].filter(Boolean).join(' '),
                subtitle: [customer.email, customer.phone].filter(Boolean).join(' • '),
                address: customer.address || '',
                email: customer.email || '',
                phone: customer.phone || '',
            };
        }

        function optionFromProduct(product) {
            const price = Number(product.sale_price ?? product.price ?? 0);

            return {
                value: String(product.id),
                text: product.name || `Sản phẩm #${product.id}`,
                searchText: [product.name, product.sku].filter(Boolean).join(' '),
                subtitle: [product.sku || 'SKU', money(price)].join(' • '),
                price,
            };
        }

        async function fetchOptions(url, mapper, query = '') {
            const response = await request(`${url}?${queryString({ q: query, per_page: 20, role: url === api.users ? 'customer' : '' })}`);
            return (response.data || []).map(mapper);
        }

        function initSelects() {
            if (!window.TomSelect) {
                loadNativeSelect(el.customerSelect, api.users, optionFromCustomer);
                loadNativeSelect(el.productSelect, api.products, optionFromProduct);
                el.customerSelect.addEventListener('change', fillCustomer);
                return;
            }

            customerSelect = createTomSelect(el.customerSelect, api.users, optionFromCustomer, fillCustomer);
            productSelect = createTomSelect(el.productSelect, api.products, optionFromProduct);
        }

        function createTomSelect(element, url, mapper, onChange = null) {
            return new TomSelect(element, {
                valueField: 'value',
                labelField: 'text',
                searchField: ['searchText', 'text', 'subtitle'],
                create: false,
                maxOptions: 20,
                preload: 'focus',
                render: {
                    option: (item, escape) => `
                        <div>
                            <div class="fw-semibold">${escape(item.text || '')}</div>
                            <div class="text-muted small">${escape(item.subtitle || '')}</div>
                        </div>`,
                    item: (item, escape) => `<div>${escape(item.text || '')}</div>`,
                },
                load: async (query, callback) => {
                    try {
                        callback(await fetchOptions(url, mapper, query));
                    } catch (error) {
                        console.warn(error);
                        callback();
                    }
                },
                onChange,
            });
        }

        async function loadNativeSelect(select, url, mapper) {
            const options = await fetchOptions(url, mapper);
            select.innerHTML = '<option value="">-- Chọn --</option>' + options.map((item) => `
                <option value="${item.value}" data-price="${item.price || ''}" data-email="${escapeHtml(item.email || '')}" data-phone="${escapeHtml(item.phone || '')}" data-address="${escapeHtml(item.address || '')}">
                    ${escapeHtml(item.text)}${item.subtitle ? ` - ${escapeHtml(item.subtitle)}` : ''}
                </option>`).join('');
        }

        function selectedCustomer() {
            if (customerSelect) {
                return customerSelect.options[customerSelect.getValue()];
            }

            const option = el.customerSelect.selectedOptions[0];
            return option ? {
                value: option.value,
                text: option.textContent.trim().split(' - ')[0],
                email: option.dataset.email || '',
                phone: option.dataset.phone || '',
                address: option.dataset.address || '',
            } : null;
        }

        function selectedProduct() {
            if (productSelect) {
                return productSelect.options[productSelect.getValue()];
            }

            const option = el.productSelect.selectedOptions[0];
            return option?.value ? {
                value: option.value,
                text: option.textContent.trim().split(' - ')[0],
                price: Number(option.dataset.price || 0),
            } : null;
        }

        function setCustomerOption(customer) {
            const option = optionFromCustomer(customer);

            if (customerSelect) {
                customerSelect.addOption(option);
                customerSelect.setValue(option.value, true);
            } else {
                el.customerSelect.add(new Option(option.text, option.value));
                el.customerSelect.value = option.value;
            }
        }

        function setSelectValue(select, value) {
            select?.setValue(value || '', true);
        }

        function syncCustomerMode() {
            const guest = el.customerModeGuest.checked;
            el.customerSelectWrap.classList.toggle('d-none', guest);

            [el.customerName, el.customerEmail, el.customerPhone].forEach((input) => {
                input.readOnly = !guest;
                input.required = guest;
            });

            if (guest) {
                customerSelect ? setSelectValue(customerSelect, '') : el.customerSelect.value = '';
            }
        }

        function fillCustomer() {
            const customer = selectedCustomer();
            if (!customer) {
                return;
            }

            el.customerName.value = customer.name || customer.text || '';
            el.customerEmail.value = customer.email || '';
            el.customerPhone.value = customer.phone || '';

            if (customer.address) {
                el.shippingAddress.value = customer.address;
            }
        }

        function renderItems() {
            el.orderItemsBody.innerHTML = state.items.length
                ? state.items.map((item, index) => `
                    <tr>
                        <td>
                            <div class="fw-semibold">${escapeHtml(item.product_name)}</div>
                            <div class="text-muted small">Mã sản phẩm: ${item.product_id}</div>
                        </td>
                        <td><input type="number" min="1" class="form-control form-control-sm" value="${item.quantity}" data-index="${index}" data-role="quantity"></td>
                        <td class="text-end">${money(item.price)}</td>
                        <td class="text-end fw-semibold">${money(Number(item.price) * Number(item.quantity))}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-danger" data-index="${index}" data-role="remove-item">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>`).join('')
                : '<tr><td colspan="5" class="text-center text-muted py-4">Chưa có sản phẩm</td></tr>';

            el.totalAmount.value = money(total());
            el.itemsTotalLabel.textContent = money(total());
        }

        function resetForm() {
            el.orderId.value = '';
            el.orderModalTitle.textContent = 'Thêm đơn hàng';
            el.customerModeExisting.checked = true;
            el.customerModeGuest.checked = false;
            syncCustomerMode();

            if (customerSelect) setSelectValue(customerSelect, '');
            if (productSelect) setSelectValue(productSelect, '');

            ['customerName', 'customerEmail', 'customerPhone', 'shippingAddress', 'note'].forEach((id) => el[id].value = '');
            el.paymentMethod.value = 'COD';
            el.orderStatus.value = 'pending';
            el.productQuantity.value = '1';
            state.items = [];
            renderItems();
        }

        function fillForm(order) {
            el.orderId.value = order.id;
            el.orderModalTitle.textContent = `Cập nhật đơn hàng #${order.id}`;

            if (order.user_id) {
                el.customerModeExisting.checked = true;
                el.customerModeGuest.checked = false;
                syncCustomerMode();
                order.user ? setCustomerOption(order.user) : setSelectValue(customerSelect, String(order.user_id));
                fillCustomer();
            } else {
                el.customerModeGuest.checked = true;
                el.customerModeExisting.checked = false;
                syncCustomerMode();
                el.customerName.value = order.customer_name || '';
                el.customerEmail.value = order.customer_email || '';
                el.customerPhone.value = order.customer_phone || '';
            }

            el.paymentMethod.value = order.payment_method || 'COD';
            el.orderStatus.value = order.status || 'pending';
            el.shippingAddress.value = order.shipping_address || '';
            el.note.value = order.note || '';
            state.items = (order.items || []).map((item) => ({
                product_id: item.product_id,
                product_name: item.product?.name || item.product_name || 'Sản phẩm',
                quantity: Number(item.quantity || 1),
                price: Number(item.price || 0),
            }));
            renderItems();
        }

        function orderPayload() {
            const userId = customerSelect ? customerSelect.getValue() : el.customerSelect.value;

            return {
                user_id: el.customerModeGuest.checked ? null : (userId || null),
                customer_name: el.customerName.value.trim(),
                customer_email: el.customerEmail.value.trim(),
                customer_phone: el.customerPhone.value.trim(),
                shipping_address: el.shippingAddress.value.trim(),
                note: el.note.value.trim(),
                payment_method: el.paymentMethod.value,
                status: el.orderStatus.value,
                items: state.items.map((item) => ({
                    product_id: item.product_id,
                    product_name: item.product_name,
                    quantity: Number(item.quantity),
                    price: Number(item.price),
                })),
            };
        }

        async function openCreateModal() {
            resetForm();
            modal.order.show();
            setTimeout(() => customerSelect?.focus(), 150);
        }

        async function openEditModal(id) {
            resetForm();
            modal.order.show();
            fillForm((await request(`${api.orders}/${id}`)).data);
        }

        async function openDetailModal(id) {
            const order = (await request(`${api.orders}/${id}`)).data;
            const customer = order.user?.name || order.customer_name || 'Khách hàng';
            const items = order.items || [];

            el.detailMeta.textContent = `#${order.id} • ${customer} • ${date(order.created_at)}`;
            el.detailBody.innerHTML = `
                <div class="row g-3 mb-4">
                    ${detailBox('Khách hàng', `<div class="fw-semibold">${escapeHtml(customer)}</div><div class="small text-muted mt-2">${escapeHtml(order.customer_email || order.user?.email || '-')}</div><div class="small text-muted">${escapeHtml(order.customer_phone || order.user?.phone || '-')}</div>`)}
                    ${detailBox('Thông tin đơn', `<div class="mb-1">Trạng thái: ${badge(order.status)}</div><div class="mb-1">Thanh toán: <span class="fw-semibold">${escapeHtml(order.payment_method || '-')}</span></div><div class="mb-1">Số sản phẩm: <span class="fw-semibold">${items.length}</span></div><div>Tổng tiền: <span class="fw-semibold text-danger">${money(order.total_amount)}</span></div>`)}
                    ${detailBox('Giao hàng', `<div class="fw-semibold">Địa chỉ</div><div class="small text-muted">${escapeHtml(order.shipping_address || '-')}</div><div class="fw-semibold mt-3">Ghi chú</div><div class="small text-muted">${escapeHtml(order.note || '-')}</div>`)}
                </div>
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead class="table-light">
                            <tr><th>Sản phẩm</th><th class="text-center">SL</th><th class="text-end">Giá</th><th class="text-end">Thành tiền</th></tr>
                        </thead>
                        <tbody>${detailRows(items)}</tbody>
                    </table>
                </div>`;
            modal.detail.show();
        }

        function detailBox(title, body) {
            return `<div class="col-lg-4"><div class="border rounded-4 p-3 h-100 bg-light-subtle"><div class="text-muted small mb-1">${title}</div>${body}</div></div>`;
        }

        function detailRows(items) {
            return items.length
                ? items.map((item) => `<tr><td>${escapeHtml(item.product?.name || item.product_name || 'Sản phẩm')}</td><td class="text-center">${item.quantity}</td><td class="text-end">${money(item.price)}</td><td class="text-end">${money(Number(item.price) * Number(item.quantity))}</td></tr>`).join('')
                : '<tr><td colspan="4" class="text-center text-muted py-3">Không có sản phẩm</td></tr>';
        }

        function openStatusModal(id) {
            const order = state.orders.find((item) => Number(item.id) === Number(id));
            el.statusOrderId.value = id;
            el.statusSelect.value = order?.status || 'pending';
            modal.status.show();
        }

        async function saveOrder() {
            const id = el.orderId.value;
            const payload = orderPayload();
            const options = {
                method: id ? 'PATCH' : 'POST',
                body: JSON.stringify(id ? { ...payload, total_amount: total() } : payload),
            };

            await request(id ? `${api.orders}/${id}` : api.orders, options);
            modal.order.hide();
            await loadOrders(state.page);
        }

        async function saveStatus() {
            await request(`${api.orders}/${el.statusOrderId.value}/status`, {
                method: 'PATCH',
                body: JSON.stringify({ status: el.statusSelect.value }),
            });
            modal.status.hide();
            await loadOrders(state.page);
        }

        async function deleteOrder(id) {
            if (!confirm('Xóa đơn hàng này?')) {
                return;
            }

            await request(`${api.orders}/${id}`, { method: 'DELETE' });
            await loadOrders(state.page);
        }

        function addSelectedProduct() {
            const product = selectedProduct();
            const quantity = Number(el.productQuantity.value || 1);

            if (!product?.value) {
                alert('Vui lòng chọn sản phẩm.');
                return;
            }

            if (quantity < 1) {
                alert('Số lượng phải lớn hơn 0.');
                return;
            }

            const existing = state.items.find((item) => Number(item.product_id) === Number(product.value));
            if (existing) {
                existing.quantity += quantity;
            } else {
                state.items.push({
                    product_id: Number(product.value),
                    product_name: product.text,
                    quantity,
                    price: Number(product.price || 0),
                });
            }

            productSelect ? setSelectValue(productSelect, '') : el.productSelect.value = '';
            el.productQuantity.value = '1';
            renderItems();
        }

        el.ordersTableBody.addEventListener('click', (event) => {
            const button = event.target.closest('[data-action]');
            if (!button) return;

            const actions = { detail: openDetailModal, edit: openEditModal, status: openStatusModal, delete: deleteOrder };
            actions[button.dataset.action]?.(button.dataset.id);
        });

        el.ordersPagination.addEventListener('click', (event) => {
            const page = Number(event.target.closest('[data-page]')?.dataset.page);
            if (page >= 1 && page <= state.meta.last_page && page !== state.meta.current_page) {
                loadOrders(page);
            }
        });

        el.orderItemsBody.addEventListener('change', (event) => {
            if (event.target.dataset.role !== 'quantity') return;

            const quantity = Number(event.target.value || 1);
            if (quantity >= 1) {
                state.items[Number(event.target.dataset.index)].quantity = quantity;
                renderItems();
            }
        });

        el.orderItemsBody.addEventListener('click', (event) => {
            const button = event.target.closest('[data-role="remove-item"]');
            if (!button) return;

            state.items.splice(Number(button.dataset.index), 1);
            renderItems();
        });

        el.createOrderBtn.addEventListener('click', openCreateModal);
        el.refreshBtn.addEventListener('click', () => loadOrders(1));
        el.customerModeExisting.addEventListener('change', syncCustomerMode);
        el.customerModeGuest.addEventListener('change', syncCustomerMode);
        el.addItemBtn.addEventListener('click', addSelectedProduct);
        el.saveOrderBtn.addEventListener('click', saveOrder);
        el.saveStatusBtn.addEventListener('click', saveStatus);
        el.statusFilter.addEventListener('change', () => {
            state.status = el.statusFilter.value;
            loadOrders(1);
        });
        el.perPageSelect.addEventListener('change', () => {
            state.perPage = Number(el.perPageSelect.value || 10);
            loadOrders(1);
        });
        el.searchInput.addEventListener('input', () => {
            state.search = el.searchInput.value.trim();
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => loadOrders(1), 300);
        });

        initSelects();
        loadOrders().catch((error) => {
            el.ordersTableBody.innerHTML = `<tr><td colspan="8" class="text-center text-danger py-5">${escapeHtml(error.message || 'Không tải được dữ liệu.')}</td></tr>`;
        });
    });
</script>
