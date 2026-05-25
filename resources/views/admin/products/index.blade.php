<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quản lý Kho Sản phẩm</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 30px;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 20px;
        }
        h1 {
            font-size: 32px;
            color: #333;
        }
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .btn-success {
            background: #48bb78;
            color: white;
        }
        .btn-danger {
            background: #f56565;
            color: white;
        }
        .btn-info {
            background: #4299e1;
            color: white;
        }
        .btn-warning {
            background: #ed8936;
            color: white;
        }
        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th {
            background: #f7fafc;
            padding: 15px;
            text-align: left;
            font-weight: bold;
            border-bottom: 2px solid #e2e8f0;
            color: #2d3748;
        }
        td {
            padding: 15px;
            border-bottom: 1px solid #e2e8f0;
        }
        tr:hover {
            background: #f7fafc;
        }
        .actions {
            display: flex;
            gap: 5px;
        }
        .status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .status.active {
            background: #c6f6d5;
            color: #22543d;
        }
        .status.inactive {
            background: #fed7d7;
            color: #742a2a;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            animation: slideIn 0.3s ease;
        }
        .alert-success {
            background: #c6f6d5;
            color: #22543d;
            border-left: 4px solid #48bb78;
        }
        .alert-error {
            background: #fed7d7;
            color: #742a2a;
            border-left: 4px solid #f56565;
        }
        .loading {
            text-align: center;
            padding: 40px;
            color: #667eea;
            font-size: 16px;
        }
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .pagination {
            display: flex;
            gap: 5px;
            justify-content: center;
            margin-top: 20px;
        }
        .pagination a, .pagination span {
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            color: #667eea;
            text-decoration: none;
            cursor: pointer;
        }
        .pagination a:hover {
            background: #667eea;
            color: white;
        }
        .pagination .active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #a0aec0;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>📦 Quản lý Kho Sản phẩm</h1>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">+ Thêm Sản phẩm</a>
        </header>

        <div id="alerts"></div>

        <div id="loading" class="loading" style="display: none;">
            <div class="spinner"></div>
            <p>Đang tải dữ liệu...</p>
        </div>

        <table id="productsTable" style="display: none;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên Sản phẩm</th>
                    <th>SKU</th>
                    <th>Danh mục</th>
                    <th>Thương hiệu</th>
                    <th>Giá</th>
                    <th>Kho</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody id="productsBody"></tbody>
        </table>

        <div id="emptyState" class="empty-state" style="display: none;">
            <p>📭 Không có sản phẩm nào</p>
        </div>

        <div id="pagination" class="pagination"></div>
    </div>

    <script>
        const API_URL = '/api/admin/products';
        let currentPage = 1;

        async function loadProducts(page = 1) {
            const loading = document.getElementById('loading');
            const table = document.getElementById('productsTable');
            const tbody = document.getElementById('productsBody');
            const empty = document.getElementById('emptyState');

            loading.style.display = 'block';
            table.style.display = 'none';
            empty.style.display = 'none';

            try {
                const response = await fetch(`${API_URL}?page=${page}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                const result = await response.json();

                if (!result.success) {
                    throw new Error(result.message);
                }

                tbody.innerHTML = '';
                const products = result.data;

                if (products.length === 0) {
                    empty.style.display = 'block';
                    loading.style.display = 'none';
                    return;
                }

                products.forEach(product => {
                    const row = `
                        <tr>
                            <td>${product.id}</td>
                            <td><strong>${product.name}</strong></td>
                            <td>${product.sku}</td>
                            <td>${product.category?.name || 'N/A'}</td>
                            <td>${product.brand?.name || 'N/A'}</td>
                            <td>${new Intl.NumberFormat('vi-VN', {style: 'currency', currency: 'VND'}).format(product.price)}</td>
                            <td>${product.stock}</td>
                            <td><span class="status ${product.is_active ? 'active' : 'inactive'}">${product.is_active ? '✓ Bán' : '✗ Ngừng'}</span></td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('admin.products.show', '') }}/${product.id}" class="btn btn-info btn-sm">Xem</a>
                                    <a href="{{ route('admin.products.edit', '') }}/${product.id}" class="btn btn-warning btn-sm">Sửa</a>
                                    <button class="btn btn-danger btn-sm" onclick="deleteProduct(${product.id})">Xóa</button>
                                </div>
                            </td>
                        </tr>
                    `;
                    tbody.innerHTML += row;
                });

                renderPagination(result.meta);
                table.style.display = 'table';
                loading.style.display = 'none';
                currentPage = page;
            } catch (error) {
                loading.style.display = 'none';
                showAlert('Lỗi: ' + error.message, 'error');
            }
        }

        function renderPagination(meta) {
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';

            if (meta.last_page <= 1) return;

            if (meta.current_page > 1) {
                pagination.innerHTML += `<a onclick="loadProducts(${meta.current_page - 1})" style="cursor: pointer;">← Trước</a>`;
            }

            for (let i = 1; i <= meta.last_page; i++) {
                if (i === meta.current_page) {
                    pagination.innerHTML += `<span class="active">${i}</span>`;
                } else {
                    pagination.innerHTML += `<a onclick="loadProducts(${i})" style="cursor: pointer;">${i}</a>`;
                }
            }

            if (meta.current_page < meta.last_page) {
                pagination.innerHTML += `<a onclick="loadProducts(${meta.current_page + 1})" style="cursor: pointer;">Tiếp →</a>`;
            }
        }

        async function deleteProduct(id) {
            if (!confirm('Bạn chắc chứ?')) return;

            try {
                const response = await fetch(`${API_URL}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                const result = await response.json();

                if (result.success) {
                    showAlert('Xóa thành công!', 'success');
                    loadProducts(currentPage);
                } else {
                    showAlert(result.message, 'error');
                }
            } catch (error) {
                showAlert('Lỗi: ' + error.message, 'error');
            }
        }

        function showAlert(message, type) {
            const alerts = document.getElementById('alerts');
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.textContent = message;
            alerts.appendChild(alert);
            setTimeout(() => alert.remove(), 4000);
        }

        loadProducts(1);
    </script>
</body>
</html>
