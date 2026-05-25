<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ strpos(Route::current()->uri, 'edit') ? 'Sửa' : 'Thêm' }} Sản phẩm</title>
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
            max-width: 800px;
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
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2d3748;
        }
        input, textarea, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e0;
            border-radius: 8px;
            font-family: inherit;
            font-size: 14px;
            transition: all 0.3s;
        }
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .form-row.full {
            grid-template-columns: 1fr;
        }
        .section {
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 1px solid #e2e8f0;
        }
        .section:last-child {
            border-bottom: none;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }
        .checkbox-group {
            display: flex;
            gap: 20px;
            align-items: center;
        }
        .checkbox-group label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: normal;
            margin-bottom: 0;
        }
        input[type="checkbox"] {
            width: auto;
            cursor: pointer;
        }
        .help-text {
            font-size: 12px;
            color: #718096;
            margin-top: 5px;
        }
        .error {
            color: #c53030;
            font-size: 12px;
            margin-top: 5px;
        }
        .btn {
            padding: 12px 30px;
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
        .btn-secondary {
            background: #cbd5e0;
            color: #2d3748;
        }
        .btn-secondary:hover {
            background: #a0aec0;
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
            .form-row {
                grid-template-columns: 1fr;
            }
            .checkbox-group {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>{{ strpos(Route::current()->uri, 'edit') ? '✏️ Sửa Sản phẩm' : '➕ Thêm Sản phẩm' }}</h1>
            <div class="breadcrumb">
                <a href="{{ route('admin.dashboard') }}">Trang chủ</a> > 
                <a href="{{ route('admin.products.index') }}">Sản phẩm</a> > 
                <span>{{ strpos(Route::current()->uri, 'edit') ? 'Sửa' : 'Thêm' }}</span>
            </div>
        </header>

        <div id="alerts"></div>

        <form id="productForm">
            <!-- Thông tin cơ bản -->
            <div class="section">
                <div class="section-title">ℹ️ Thông tin cơ bản</div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Tên Sản phẩm *</label>
                        <input type="text" id="name" name="name" required>
                        <div class="error" id="error-name"></div>
                    </div>

                    <div class="form-group">
                        <label for="sku">SKU *</label>
                        <input type="text" id="sku" name="sku" placeholder="VD: LAP-ASUS-001" required>
                        <div class="error" id="error-sku"></div>
                    </div>
                </div>

                <div class="form-group form-row full">
                    <label for="slug">Slug (URL)</label>
                    <input type="text" id="slug" name="slug">
                    <div class="help-text">Để trống để tự động sinh từ tên</div>
                </div>

                <div class="form-group form-row full">
                    <label for="summary">Mô tả ngắn</label>
                    <textarea id="summary" name="summary"></textarea>
                </div>

                <div class="form-group form-row full">
                    <label for="description">Mô tả chi tiết</label>
                    <textarea id="description" name="description"></textarea>
                </div>
            </div>

            <!-- Giá & Kho -->
            <div class="section">
                <div class="section-title">💰 Giá & Kho</div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="price">Giá bán *</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" required>
                        <div class="error" id="error-price"></div>
                    </div>

                    <div class="form-group">
                        <label for="sale_price">Giá khuyến mãi</label>
                        <input type="number" id="sale_price" name="sale_price" step="0.01" min="0">
                    </div>
                </div>

                <div class="form-group form-row full">
                    <label for="stock">Số lượng kho *</label>
                    <input type="number" id="stock" name="stock" min="0" required>
                    <div class="error" id="error-stock"></div>
                </div>
            </div>

            <!-- Phân loại -->
            <div class="section">
                <div class="section-title">📂 Phân loại</div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="category_id">Danh mục *</label>
                        <select id="category_id" name="category_id" required>
                            <option value="">-- Chọn danh mục --</option>
                        </select>
                        <div class="error" id="error-category_id"></div>
                    </div>

                    <div class="form-group">
                        <label for="brand_id">Thương hiệu *</label>
                        <select id="brand_id" name="brand_id" required>
                            <option value="">-- Chọn thương hiệu --</option>
                        </select>
                        <div class="error" id="error-brand_id"></div>
                    </div>
                </div>
            </div>

            <!-- Hình ảnh -->
            <div class="section">
                <div class="section-title">🖼️ Hình ảnh</div>

                <div class="form-group form-row full">
                    <label for="thumbnail">Hình ảnh đại diện *</label>
                    <input type="text" id="thumbnail" name="thumbnail" placeholder="https://..." required>
                    <div class="help-text">URL hình ảnh</div>
                    <div class="error" id="error-thumbnail"></div>
                </div>
            </div>

            <!-- Trạng thái -->
            <div class="section">
                <div class="section-title">⚙️ Trạng thái</div>

                <div class="checkbox-group">
                    <label>
                        <input type="checkbox" id="is_active" name="is_active" checked>
                        Bán sản phẩm
                    </label>

                    <label>
                        <input type="checkbox" id="is_featured" name="is_featured">
                        Sản phẩm nổi bật
                    </label>
                </div>
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-primary">{{ strpos(Route::current()->uri, 'edit') ? '💾 Cập nhật' : '➕ Thêm' }}</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">❌ Hủy</a>
            </div>
        </form>
    </div>

    <script>
        const API_URL = '/api/admin/products';
        const isEdit = {{ strpos(Route::current()->uri, 'edit') ? 'true' : 'false' }};
        const productId = {{ request()->route('id') ?? 'null' }};

        async function loadOptions() {
            try {
                const [catRes, brandRes] = await Promise.all([
                    fetch('/api/categories'),
                    fetch('/api/brands')
                ]);

                const cats = await catRes.json();
                const brands = await brandRes.json();

                if (cats.success) {
                    cats.data.forEach(cat => {
                        const opt = document.createElement('option');
                        opt.value = cat.id;
                        opt.textContent = cat.name;
                        document.getElementById('category_id').appendChild(opt);
                    });
                }

                if (brands.success) {
                    brands.data.forEach(brand => {
                        const opt = document.createElement('option');
                        opt.value = brand.id;
                        opt.textContent = brand.name;
                        document.getElementById('brand_id').appendChild(opt);
                    });
                }

                if (isEdit && productId) {
                    const res = await fetch(`${API_URL}/${productId}`);
                    const data = await res.json();
                    if (data.success) {
                        const p = data.data;
                        document.getElementById('name').value = p.name;
                        document.getElementById('slug').value = p.slug;
                        document.getElementById('sku').value = p.sku;
                        document.getElementById('summary').value = p.summary || '';
                        document.getElementById('description').value = p.description || '';
                        document.getElementById('price').value = p.price;
                        document.getElementById('sale_price').value = p.sale_price || '';
                        document.getElementById('stock').value = p.stock;
                        document.getElementById('thumbnail').value = p.thumbnail;
                        document.getElementById('category_id').value = p.category_id;
                        document.getElementById('brand_id').value = p.brand_id;
                        document.getElementById('is_active').checked = p.is_active;
                        document.getElementById('is_featured').checked = p.is_featured;
                    }
                }
            } catch (error) {
                showAlert('Lỗi tải dữ liệu: ' + error.message, 'error');
            }
        }

        document.getElementById('productForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            document.querySelectorAll('.error').forEach(el => el.textContent = '');

            const data = {
                name: document.getElementById('name').value,
                slug: document.getElementById('slug').value || undefined,
                sku: document.getElementById('sku').value,
                summary: document.getElementById('summary').value || undefined,
                description: document.getElementById('description').value || undefined,
                price: parseFloat(document.getElementById('price').value),
                sale_price: document.getElementById('sale_price').value ? parseFloat(document.getElementById('sale_price').value) : undefined,
                stock: parseInt(document.getElementById('stock').value),
                thumbnail: document.getElementById('thumbnail').value,
                is_active: document.getElementById('is_active').checked,
                is_featured: document.getElementById('is_featured').checked,
                category_id: parseInt(document.getElementById('category_id').value),
                brand_id: parseInt(document.getElementById('brand_id').value)
            };

            try {
                const url = isEdit ? `${API_URL}/${productId}` : API_URL;
                const method = isEdit ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    showAlert(result.message, 'success');
                    setTimeout(() => {
                        window.location.href = '{{ route("admin.products.index") }}';
                    }, 1500);
                } else {
                    if (result.data && typeof result.data === 'object') {
                        Object.keys(result.data).forEach(field => {
                            const el = document.getElementById(`error-${field}`);
                            if (el) {
                                el.textContent = Array.isArray(result.data[field]) ? result.data[field][0] : result.data[field];
                            }
                        });
                    }
                    showAlert(result.message, 'error');
                }
            } catch (error) {
                showAlert('Lỗi: ' + error.message, 'error');
            }
        });

        function showAlert(msg, type) {
            const alerts = document.getElementById('alerts');
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.textContent = msg;
            alerts.appendChild(alert);
            setTimeout(() => alert.remove(), 4000);
        }

        loadOptions();
    </script>
</body>
</html>
