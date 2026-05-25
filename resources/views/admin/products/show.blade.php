<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chi tiết Sản phẩm</title>
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
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 40px;
        }
        header {
            margin-bottom: 30px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 20px;
        }
        h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 5px;
        }
        .breadcrumb {
            color: #718096;
            font-size: 14px;
        }
        .breadcrumb a {
            color: #667eea;
            text-decoration: none;
        }
        .loading {
            text-align: center;
            padding: 40px;
            color: #667eea;
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
        .product-detail {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        .product-image {
            background: #f7fafc;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 300px;
            border: 2px solid #e2e8f0;
        }
        .product-image img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            border-radius: 8px;
        }
        .product-info {
            display: flex;
            flex-direction: column;
        }
        .product-name {
            font-size: 28px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 10px;
        }
        .product-sku {
            color: #718096;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .price-section {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e2e8f0;
        }
        .price {
            font-size: 28px;
            font-weight: bold;
            color: #f56565;
        }
        .original-price {
            color: #a0aec0;
            text-decoration: line-through;
            margin-left: 10px;
            font-size: 20px;
        }
        .info-row {
            display: grid;
            grid-template-columns: 150px 1fr;
            gap: 15px;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e2e8f0;
        }
        .info-label {
            font-weight: bold;
            color: #2d3748;
        }
        .info-value {
            color: #4a5568;
        }
        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
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
        .status.featured {
            background: #bee3f8;
            color: #2c5282;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }
        .description-text {
            color: #4a5568;
            line-height: 1.6;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .images-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 15px;
        }
        .gallery-item {
            background: #f7fafc;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            aspect-ratio: 1;
            border: 1px solid #e2e8f0;
        }
        .gallery-item img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background: #f7fafc;
            padding: 12px;
            text-align: left;
            font-weight: bold;
            border-bottom: 2px solid #e2e8f0;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
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
        }
        .btn-danger {
            background: #f56565;
            color: white;
        }
        .btn-danger:hover {
            background: #e53e3e;
        }
        .btn-secondary {
            background: #cbd5e0;
            color: #2d3748;
        }
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
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
        @media (max-width: 600px) {
            .product-detail {
                grid-template-columns: 1fr;
            }
            .images-gallery {
                grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            }
        }
    </style>
</head>
<body>
    <div class="container" data-product-id="{{ request()->route('id') }}">
        <header>
            <h1>👁️ Chi tiết Sản phẩm</h1>
            <div class="breadcrumb">
                <a href="{{ route('admin.dashboard') }}">Trang chủ</a> > 
                <a href="{{ route('admin.products.index') }}">Sản phẩm</a> > 
                <span id="breadcrumb-name">Chi tiết</span>
            </div>
        </header>

        <div id="alerts"></div>

        <div id="loading" class="loading">
            <div class="spinner"></div>
            <p>Đang tải dữ liệu...</p>
        </div>

        <div id="content" style="display: none;">
            <!-- Thông tin chính -->
            <div class="product-detail">
                <div class="product-image">
                    <img id="thumbnail" src="" alt="Sản phẩm">
                </div>

                <div class="product-info">
                    <div class="product-name" id="name"></div>
                    <div class="product-sku">SKU: <strong id="sku"></strong></div>

                    <div class="price-section">
                        <div>
                            <span class="price" id="price"></span>
                            <span class="original-price" id="sale_price" style="display: none;"></span>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Trạng thái:</div>
                        <div class="info-value">
                            <span class="status" id="status-badge"></span>
                            <span class="status featured" id="featured-badge" style="display: none; margin-left: 10px;">⭐ Nổi bật</span>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Kho hàng:</div>
                        <div class="info-value"><strong id="stock"></strong> sản phẩm</div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Danh mục:</div>
                        <div class="info-value" id="category"></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Thương hiệu:</div>
                        <div class="info-value" id="brand"></div>
                    </div>

                    <div class="info-row" style="border: none;">
                        <div class="info-label">Slug:</div>
                        <div class="info-value" id="slug"></div>
                    </div>
                </div>
            </div>

            <!-- Mô tả -->
            <div class="info-row">
                <div class="info-label">Mô tả ngắn:</div>
                <div class="info-value" id="summary"></div>
            </div>

            <!-- Mô tả chi tiết -->
            <div class="section">
                <div class="section-title">📝 Mô tả chi tiết</div>
                <div class="description-text" id="description"></div>
            </div>

            <!-- Hình ảnh phụ -->
            <div id="images-section" style="display: none;" class="section">
                <div class="section-title">🖼️ Hình ảnh phụ</div>
                <div class="images-gallery" id="images-gallery"></div>
            </div>

            <!-- Thông số kỹ thuật -->
            <div id="attributes-section" style="display: none;" class="section">
                <div class="section-title">⚙️ Thông số kỹ thuật</div>
                <table>
                    <thead>
                        <tr>
                            <th>Tên</th>
                            <th>Giá trị</th>
                        </tr>
                    </thead>
                    <tbody id="attributes-body"></tbody>
                </table>
            </div>

            <!-- Nút hành động -->
            <div class="button-group">
                <a href="#" id="edit-btn" class="btn btn-primary">✏️ Sửa</a>
                <button id="delete-btn" class="btn btn-danger">🗑️ Xóa</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">← Quay lại</a>
            </div>
        </div>
    </div>

    <script>
        const API_URL = '/api/admin/products';
        const productId = document.querySelector('[data-product-id]')?.dataset.productId;

        async function loadProduct() {
            const loading = document.getElementById('loading');
            const content = document.getElementById('content');

            try {
                const response = await fetch(`${API_URL}/${productId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                const result = await response.json();

                if (!result.success) {
                    throw new Error(result.message);
                }

                displayProduct(result.data);

                loading.style.display = 'none';
                content.style.display = 'block';

                document.getElementById('edit-btn').href = `{{ route('admin.products.edit', '') }}/${productId}`;
                document.getElementById('delete-btn').addEventListener('click', deleteProduct);

            } catch (error) {
                loading.style.display = 'none';
                showAlert('Lỗi: ' + error.message, 'error');
            }
        }

        function displayProduct(p) {
            document.getElementById('breadcrumb-name').textContent = p.name;
            document.getElementById('name').textContent = p.name;
            document.getElementById('sku').textContent = p.sku;
            document.getElementById('slug').textContent = p.slug;
            document.getElementById('stock').textContent = p.stock;
            document.getElementById('summary').textContent = p.summary || '(Không có)';
            document.getElementById('description').textContent = p.description || '(Không có)';
            document.getElementById('thumbnail').src = p.thumbnail;

            document.getElementById('price').textContent = new Intl.NumberFormat('vi-VN', {style: 'currency', currency: 'VND'}).format(p.price);
            if (p.sale_price) {
                document.getElementById('sale_price').style.display = 'inline';
                document.getElementById('sale_price').textContent = new Intl.NumberFormat('vi-VN', {style: 'currency', currency: 'VND'}).format(p.sale_price);
            }

            document.getElementById('category').textContent = p.category?.name || 'N/A';
            document.getElementById('brand').textContent = p.brand?.name || 'N/A';

            const statusBadge = document.getElementById('status-badge');
            if (p.is_active) {
                statusBadge.textContent = '✓ Đang bán';
                statusBadge.className = 'status active';
            } else {
                statusBadge.textContent = '✗ Ngừng bán';
                statusBadge.className = 'status inactive';
            }

            if (p.is_featured) {
                document.getElementById('featured-badge').style.display = 'inline-block';
            }

            if (p.images && p.images.length > 0) {
                const gallery = document.getElementById('images-gallery');
                gallery.innerHTML = '';
                p.images.forEach(img => {
                    const item = document.createElement('div');
                    item.className = 'gallery-item';
                    item.innerHTML = `<img src="${img.image_path}" alt="Ảnh phụ">`;
                    gallery.appendChild(item);
                });
                document.getElementById('images-section').style.display = 'block';
            }

            if (p.attributes && p.attributes.length > 0) {
                const tbody = document.getElementById('attributes-body');
                tbody.innerHTML = '';
                p.attributes.forEach(attr => {
                    tbody.innerHTML += `<tr><td>${attr.name}</td><td>${attr.value}</td></tr>`;
                });
                document.getElementById('attributes-section').style.display = 'block';
            }
        }

        async function deleteProduct() {
            if (!confirm('Xóa sản phẩm này?')) return;

            try {
                const response = await fetch(`${API_URL}/${productId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                const result = await response.json();

                if (result.success) {
                    showAlert('Xóa thành công!', 'success');
                    setTimeout(() => {
                        window.location.href = '{{ route("admin.products.index") }}';
                    }, 1500);
                } else {
                    showAlert(result.message, 'error');
                }
            } catch (error) {
                showAlert('Lỗi: ' + error.message, 'error');
            }
        }

        function showAlert(msg, type) {
            const alerts = document.getElementById('alerts');
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.textContent = msg;
            alerts.appendChild(alert);
            setTimeout(() => alert.remove(), 4000);
        }

        loadProduct();
    </script>
</body>
</html>
