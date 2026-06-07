@extends('admin.master')

@section('title', 'Thêm sản phẩm')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <p class="text-uppercase text-muted small mb-1">Admin / Products</p>
            <h1 class="h3 mb-0">Thêm Sản phẩm Mới</h1>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form id="productForm" method="POST">
                @csrf

                <div class="mb-4">
                    <h5 class="border-bottom pb-2 mb-3">Thông tin cơ bản</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Tên Sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="sku" class="form-label">SKU <span class="text-danger">*</span></label>
                            <input type="text" id="sku" name="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku') }}" required>
                            @error('sku')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="slug" class="form-label">Slug (URL thân thiện)</label>
                            <input type="text" id="slug" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}" placeholder="Để trống tự động tạo từ tên">
                            @error('slug')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="summary" class="form-label">Mô tả ngắn</label>
                        <textarea id="summary" name="summary" class="form-control @error('summary') is-invalid @enderror" rows="3" placeholder="Mô tả ngắn về sản phẩm">{{ old('summary') }}</textarea>
                        @error('summary')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả chi tiết</label>
                        <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="5" placeholder="Mô tả chi tiết sản phẩm">{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-4">
                    <h5 class="border-bottom pb-2 mb-3">Giá & Kho hàng</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="price" class="form-label">Giá gốc (đ) <span class="text-danger">*</span></label>
                            <input type="number" id="price" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" step="0.01" min="0" required>
                            @error('price')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="sale_price" class="form-label">Giá khuyến mãi (đ)</label>
                            <input type="number" id="sale_price" name="sale_price" class="form-control @error('sale_price') is-invalid @enderror" value="{{ old('sale_price') }}" step="0.01" min="0">
                            @error('sale_price')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="stock" class="form-label">Số lượng tồn kho <span class="text-danger">*</span></label>
                            <input type="number" id="stock" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', 0) }}" min="0" required>
                            @error('stock')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h5 class="border-bottom pb-2 mb-3">Phân loại & Ảnh</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
                            <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="brand_id" class="form-label">Thương hiệu <span class="text-danger">*</span></label>
                            <select id="brand_id" name="brand_id" class="form-select @error('brand_id') is-invalid @enderror" required>
                                <option value="">-- Chọn thương hiệu --</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                            @error('brand_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <!-- Ảnh Thumbnail -->
                    <div class="mb-4">
                        <label for="thumbnail" class="form-label">Ảnh Đại Diện (Ảnh Chính) <span class="text-danger">*</span></label>
                        <input type="file" id="thumbnail" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror" accept="image/*" required>
                        <small class="text-muted d-block mt-1">Chọn ảnh chính của sản phẩm</small>
                        @error('thumbnail')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        
                        <div id="thumbnailPreview" class="mt-3" style="display: none;">
                            <img id="thumbnailImage" src="" alt="Thumbnail Preview" class="img-fluid rounded" style="max-width: 300px; max-height: 300px; object-fit: cover;">
                        </div>
                    </div>

                    <!-- Ảnh Chi Tiết Sản Phẩm -->
                    <div class="mb-4">
                        <label for="images" class="form-label">Ảnh Chi Tiết Sản Phẩm</label>
                        <input type="file" id="images" name="images[]" class="form-control @error('images') is-invalid @enderror" accept="image/*" multiple>
                        <small class="text-muted d-block mt-1">Chọn nhiều ảnh chi tiết (có thể chọn 0 hoặc nhiều ảnh)</small>
                        @error('images')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

                        <div id="imagesPreview" class="mt-3 d-flex gap-2 flex-wrap">
                            <!-- Preview images will appear here -->
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h5 class="border-bottom pb-2 mb-3">Trạng thái</h5>
                    <div class="form-check form-switch">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" id="is_active" name="is_active" class="form-check-input" value="1" {{ old('is_active') ? 'checked' : '' }}>
                        <label for="is_active" class="form-check-label">Bán (Hiển thị & cho phép mua)</label>
                    </div>

                    <div class="form-check form-switch mt-2">
                        <input type="hidden" name="is_featured" value="0">
                        <input type="checkbox" id="is_featured" name="is_featured" class="form-check-input" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                        <label for="is_featured" class="form-check-label">Nổi bật (Hiển thị trên trang chủ)</label>
                    </div>
                </div>

                <div class="mb-3">
                    <button type="button" class="btn btn-primary btn-lg" onclick="submitForm()">
                        <i class="bi bi-check-circle"></i> Tạo Sản phẩm
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-lg">
                        <i class="bi bi-x-circle"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Thumbnail preview
    document.getElementById('thumbnail').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('thumbnailImage').src = event.target.result;
                document.getElementById('thumbnailPreview').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });

    // Multiple images preview
    document.getElementById('images').addEventListener('change', function(e) {
        const previewContainer = document.getElementById('imagesPreview');
        previewContainer.innerHTML = '';

        const files = Array.from(e.target.files);
        files.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(event) {
                const imgWrapper = document.createElement('div');
                imgWrapper.className = 'position-relative';
                imgWrapper.style.cssText = 'width: 100px; height: 100px;';
                
                const img = document.createElement('img');
                img.src = event.target.result;
                img.className = 'img-fluid rounded border';
                img.style.cssText = 'width: 100%; height: 100%; object-fit: cover;';
                
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'btn btn-sm btn-danger position-absolute top-0 end-0';
                removeBtn.innerHTML = '&times;';
                removeBtn.style.cssText = 'width: 24px; height: 24px; padding: 0; border-radius: 50%; margin: -5px -5px 0 0;';
                removeBtn.onclick = function(e) {
                    e.preventDefault();
                    imgWrapper.remove();
                };

                imgWrapper.appendChild(img);
                imgWrapper.appendChild(removeBtn);
                previewContainer.appendChild(imgWrapper);
            };
            reader.readAsDataURL(file);
        });
    });

    async function submitForm() {
        const form = document.getElementById('productForm');
        const formData = new FormData(form);

        try {
            const response = await fetch('/api/admin/products', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok && data.success) {
                window.location.href = '{{ route("admin.products.index") }}';
            } else {
                const errors = data.data?.errors || data.message || 'Lỗi không xác định';
                alert(typeof errors === 'string' ? errors : JSON.stringify(errors));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Lỗi khi tạo sản phẩm');
        }
    }
</script>
@endpush
