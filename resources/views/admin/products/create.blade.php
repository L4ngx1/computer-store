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
            <form id="productForm" method="POST" data-admin-product-form data-api-url="{{ route('admin.products.api.store', [], false) }}">
                @csrf

                <div class="mb-4 product-edit-section--basic">
                    <h5 class="border-bottom pb-2 mb-3">Thông tin cơ bản</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="name" class="form-label">Tên Sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="sku" class="form-label">SKU <span class="text-danger">*</span></label>
                            <input type="text" id="sku" name="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku') }}" required>
                            @error('sku')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="slug" class="form-label">Slug (URL thân thiện)</label>
                            <input type="text" id="slug" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}" placeholder="Để trống tự động tạo từ tên">
                            @error('slug')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label for="summary" class="form-label">Mô tả ngắn</label>
                            <textarea id="summary" name="summary" class="form-control @error('summary') is-invalid @enderror" rows="2" placeholder="Mô tả ngắn về sản phẩm">{{ old('summary') }}</textarea>
                            @error('summary')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="description" class="form-label">Mô tả chi tiết</label>
                            <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="2" placeholder="Mô tả chi tiết sản phẩm">{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="mb-4 product-edit-section--pricing">
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

                <div class="mb-4 product-edit-section--warranty">
                    <h5 class="border-bottom pb-2 mb-3">Bảo hành</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="warranty_start_date" class="form-label">Ngày bắt đầu</label>
                            <input type="date" id="warranty_start_date" name="warranty_start_date"
                                   class="form-control @error('warranty_start_date') is-invalid @enderror"
                                   value="{{ old('warranty_start_date') }}">
                            @error('warranty_start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="warranty_end_date" class="form-label">Ngày kết thúc</label>
                            <input type="date" id="warranty_end_date" name="warranty_end_date"
                                   class="form-control @error('warranty_end_date') is-invalid @enderror"
                                   value="{{ old('warranty_end_date') }}">
                            @error('warranty_end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="mb-4 product-edit-section--classification">
                    <h5 class="border-bottom pb-2 mb-3">Phân loại & ảnh</h5>
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

           
                    <div class="product-media-grid row g-3">
                        <div class="col-lg-6">
                            <section class="border rounded-3 p-3 bg-white h-100">
                                <div class="mb-2">
                                    <div>
                                        <h6 class="mb-0">Ảnh đại diện</h6>
                                    </div>
                                </div>
                                <input type="file" id="thumbnail" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror" accept="image/png,image/jpeg,image/webp" required onchange="previewProductThumbnail(this)">
                                @error('thumbnail')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                <div id="thumbnailPreview" class="product-gallery-grid mt-2 d-none"></div>
                            </section>
                        </div>

                        <div class="col-lg-6">
                            <section class="border rounded-3 p-3 bg-white h-100">
                                <div class="mb-2">
                                    <div>
                                        <h6 class="mb-0">Ảnh chi tiết</h6>
                                    </div>
                                </div>
                                <input type="file" id="images" name="images[]" class="form-control @error('images') is-invalid @enderror" accept="image/png,image/jpeg,image/webp" multiple onchange="previewProductGallery(this)">
                                @error('images')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                <div id="imagesPreview" class="product-gallery-grid mt-2"></div>
                            </section>
                        </div>
                    </div>
                    
                </div>

                <div class="mb-4 product-edit-section--status">
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

                <div class="mb-3 product-edit-actions d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        Tạo sản phẩm
                    </button>
                    <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">Đóng</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
