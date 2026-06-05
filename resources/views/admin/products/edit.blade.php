@extends('admin.master')

@section('title', 'Chỉnh sửa sản phẩm')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <p class="text-uppercase text-muted small mb-1">Admin / Products</p>
            <h1 class="h3 mb-0">Chỉnh sửa Sản phẩm</h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-info text-white">
                <i class="bi bi-eye"></i> Xem chi tiết
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form id="productForm" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <h5 class="border-bottom pb-2 mb-3">Thông tin cơ bản</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Tên Sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
                            @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="sku" class="form-label">SKU <span class="text-danger">*</span></label>
                            <input type="text" id="sku" name="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku', $product->sku) }}" required>
                            @error('sku')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="slug" class="form-label">Slug (URL thân thiện)</label>
                            <input type="text" id="slug" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $product->slug) }}">
                            @error('slug')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="summary" class="form-label">Mô tả ngắn</label>
                        <textarea id="summary" name="summary" class="form-control @error('summary') is-invalid @enderror" rows="3">{{ old('summary', $product->summary) }}</textarea>
                        @error('summary')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả chi tiết</label>
                        <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="5">{{ old('description', $product->description) }}</textarea>
                        @error('description')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-4">
                    <h5 class="border-bottom pb-2 mb-3">Giá & Kho hàng</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="price" class="form-label">Giá gốc (đ) <span class="text-danger">*</span></label>
                            <input type="number" id="price" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}" step="0.01" min="0" required>
                            @error('price')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="sale_price" class="form-label">Giá khuyến mãi (đ)</label>
                            <input type="number" id="sale_price" name="sale_price" class="form-control @error('sale_price') is-invalid @enderror" value="{{ old('sale_price', $product->sale_price) }}" step="0.01" min="0">
                            @error('sale_price')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="stock" class="form-label">Số lượng tồn kho <span class="text-danger">*</span></label>
                            <input type="number" id="stock" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $product->stock) }}" min="0" required>
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
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="brand_id" class="form-label">Thương hiệu <span class="text-danger">*</span></label>
                            <select id="brand_id" name="brand_id" class="form-select @error('brand_id') is-invalid @enderror" required>
                                <option value="">-- Chọn thương hiệu --</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                            @error('brand_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="thumbnail" class="form-label">Ảnh đại diện (URL) <span class="text-danger">*</span></label>
                        <input type="url" id="thumbnail" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror" value="{{ old('thumbnail', $product->thumbnail) }}" required>
                        @error('thumbnail')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-4">
                    <h5 class="border-bottom pb-2 mb-3">Trạng thái</h5>
                    <div class="form-check form-switch">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" id="is_active" name="is_active" class="form-check-input" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        <label for="is_active" class="form-check-label">Bán (Hiển thị & cho phép mua)</label>
                    </div>

                    <div class="form-check form-switch mt-2">
                        <input type="hidden" name="is_featured" value="0">
                        <input type="checkbox" id="is_featured" name="is_featured" class="form-check-input" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                        <label for="is_featured" class="form-check-label">Nổi bật (Hiển thị trên trang chủ)</label>
                    </div>
                </div>

                <div class="mb-3">
                    <button type="button" class="btn btn-primary btn-lg" onclick="submitForm()">
                        <i class="bi bi-check-circle"></i> Cập nhật Sản phẩm
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-lg">
                        <i class="bi bi-x-circle"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    async function submitForm() {
        const form = document.getElementById('productForm');
        const formData = new FormData(form);

        try {
            const response = await fetch('/api/admin/products/{{ $product->id }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-HTTP-Method-Override': 'PUT',
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok && data.success) {
                window.location.href = '{{ route("admin.products.show", $product->id) }}';
            } else {
                const errors = data.data?.errors || data.message || 'Lỗi không xác định';
                alert(typeof errors === 'string' ? errors : JSON.stringify(errors));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Lỗi khi cập nhật sản phẩm');
        }
    }
</script>
@endpush