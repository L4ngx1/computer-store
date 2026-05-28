<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Tạo danh mục mẫu (Categories)
        $catLaptop = Category::updateOrCreate(
            ['slug' => 'laptop'],
            ['name' => 'Laptop', 'image' => 'cat_laptop.jpg', 'is_active' => true]
        );
        $catPC = Category::updateOrCreate(
            ['slug' => 'pc-dong-bo'],
            ['name' => 'PC Đồng Bộ', 'image' => 'cat_pc.jpg', 'is_active' => true]
        );
        $catLinhKien = Category::updateOrCreate(
            ['slug' => 'linh-kien'],
            ['name' => 'Linh Kiện Máy Tính', 'image' => 'cat_linhkien.jpg', 'is_active' => true]
        );

        // 2. Tạo thương hiệu mẫu (Brands)
        $brandAsus = Brand::updateOrCreate(
            ['slug' => 'asus'],
            ['name' => 'ASUS', 'logo' => 'logo_asus.png']
        );
        $brandMsi = Brand::updateOrCreate(
            ['slug' => 'msi'],
            ['name' => 'MSI', 'logo' => 'logo_msi.png']
        );
        $brandNvidia = Brand::updateOrCreate(
            ['slug' => 'nvidia'],
            ['name' => 'NVIDIA', 'logo' => 'logo_nvidia.png']
        );

        // 3. Tạo sản phẩm mẫu (Products)

        // Sản phẩm 1: Laptop Gaming ASUS
        $p1 = Product::updateOrCreate(
            ['sku' => 'LAP-ASUS-ROG01'],
            [
                'name' => 'Laptop Gaming ASUS ROG Strix G16',
                'slug' => Str::slug('Laptop Gaming ASUS ROG Strix G16'),
            'summary' => 'Laptop gaming đỉnh cao với CPU Intel Core i7 thế hệ mới và card đồ họa RTX 4060.',
            'description' => 'Sở hữu hiệu năng vượt trội, hệ thống tản nhiệt thông minh và màn hình 165Hz siêu mượt mà. Phù hợp cho cả game thủ và đồ họa chuyên nghiệp.',
                'price' => 35990000,
                'sale_price' => 33990000,
                'stock' => 15,
                'thumbnail' => 'products/rog_strix_g16.jpg',
                'is_featured' => true,
                'is_active' => true,
                'category_id' => $catLaptop->id,
                'brand_id' => $brandAsus->id,
            ]
        );

        $p1->attributes()->delete();
        $p1->images()->delete();
        ProductAttribute::create(['product_id' => $p1->id, 'name' => 'CPU', 'value' => 'Core i7']);
        ProductAttribute::create(['product_id' => $p1->id, 'name' => 'RAM', 'value' => '16GB']);
        ProductAttribute::create(['product_id' => $p1->id, 'name' => 'VGA', 'value' => 'RTX 4060']);
        ProductAttribute::create(['product_id' => $p1->id, 'name' => 'Ổ cứng', 'value' => '512GB SSD']);
        ProductImage::create(['product_id' => $p1->id, 'image_path' => 'products/rog_strix_side1.jpg']);
        ProductImage::create(['product_id' => $p1->id, 'image_path' => 'products/rog_strix_side2.jpg']);

        // Sản phẩm 2: Card màn hình MSI
        $p2 = Product::updateOrCreate(
            ['sku' => 'VGA-MSI-4070TIX'],
            [
            'name' => 'Card Màn Hình MSI GeForce RTX 4070 Ti GAMING X SLIM',
            'slug' => Str::slug('Card Màn Hình MSI GeForce RTX 4070 Ti GAMING X SLIM'),
            'summary' => 'Card đồ họa hiệu năng cao thế hệ Ada Lovelace siêu mát, siêu mỏng.',
            'description' => 'Được trang bị kiến trúc NVIDIA mới nhất, kiến tạo thế giới ảo mượt mà với công nghệ Ray Tracing thế hệ thứ 3 cùng DLSS 3 ổn định khung hình.',
                'price' => 24500000,
                'sale_price' => 23900000,
                'stock' => 8,
                'thumbnail' => 'products/msi_rtx4070ti.jpg',
                'is_featured' => true,
                'is_active' => true,
                'category_id' => $catLinhKien->id,
                'brand_id' => $brandMsi->id,
            ]
        );

        $p2->attributes()->delete();
        $p2->images()->delete();
        ProductAttribute::create(['product_id' => $p2->id, 'name' => 'VGA', 'value' => 'RTX 4070 Ti']);
        ProductAttribute::create(['product_id' => $p2->id, 'name' => 'Dung lượng VRAM', 'value' => '12GB']);

        // Sản phẩm 3: PC Đồng Bộ ASUS
        $p3 = Product::updateOrCreate(
            ['sku' => 'PC-ASUS-G10DK'],
            [
            'name' => 'PC Đồng Bộ ASUS ROG Strix G10DK',
            'slug' => Str::slug('PC Đồng Bộ ASUS ROG Strix G10DK'),
            'summary' => 'Máy tính để bàn cấu hình sẵn mạnh mẽ, ổn định.',
            'description' => 'Bộ máy chiến game tối ưu trang bị chip AMD Ryzen 5, card đồ họa rời GTX 1660 Ti, sẵn sàng cân tốt các tựa game Esport hiện tại.',
                'price' => 18990000,
                'sale_price' => null,
                'stock' => 5,
                'thumbnail' => 'products/asus_pc_g10.jpg',
                'is_featured' => false,
                'is_active' => true,
                'category_id' => $catPC->id,
                'brand_id' => $brandAsus->id,
            ]
        );

        $p3->attributes()->delete();
        $p3->images()->delete();
        ProductAttribute::create(['product_id' => $p3->id, 'name' => 'CPU', 'value' => 'Ryzen 5']);
        ProductAttribute::create(['product_id' => $p3->id, 'name' => 'RAM', 'value' => '8GB']);
        ProductAttribute::create(['product_id' => $p3->id, 'name' => 'VGA', 'value' => 'GTX 1660 Ti']);

        // Sản phẩm 4: Laptop văn phòng ASUS
        $p4 = Product::updateOrCreate(
            ['sku' => 'LAP-ASUS-VIVO15'],
            [
                'name' => 'Laptop ASUS Vivobook 15 X1504ZA',
                'slug' => Str::slug('Laptop ASUS Vivobook 15 X1504ZA'),
                'summary' => 'Laptop mỏng nhẹ cho học tập và văn phòng, hiệu năng ổn định.',
                'description' => 'Trang bị Intel Core i5, SSD tốc độ cao và màn hình 15.6 inch, phù hợp cho sinh viên và dân văn phòng cần máy gọn, bền, pin tốt.',
                'price' => 15990000,
                'sale_price' => 14990000,
                'stock' => 20,
                'thumbnail' => 'products/asus_vivobook_15.jpg',
                'is_featured' => false,
                'is_active' => true,
                'category_id' => $catLaptop->id,
                'brand_id' => $brandAsus->id,
            ]
        );

        $p4->attributes()->delete();
        $p4->images()->delete();
        ProductAttribute::create(['product_id' => $p4->id, 'name' => 'CPU', 'value' => 'Core i5']);
        ProductAttribute::create(['product_id' => $p4->id, 'name' => 'RAM', 'value' => '8GB']);
        ProductAttribute::create(['product_id' => $p4->id, 'name' => 'Ổ cứng', 'value' => '512GB SSD']);

        // Sản phẩm 5: SSD MSI
        $p5 = Product::updateOrCreate(
            ['sku' => 'SSD-MSI-1TB'],
            [
                'name' => 'SSD MSI Spatium M482 1TB',
                'slug' => Str::slug('SSD MSI Spatium M482 1TB'),
                'summary' => 'SSD NVMe tốc độ cao cho máy tính bàn và laptop.',
                'description' => 'Ổ cứng SSD chuẩn PCIe 4.0 cho tốc độ đọc ghi vượt trội, rút ngắn thời gian khởi động hệ thống và ứng dụng.',
                'price' => 2290000,
                'sale_price' => 1990000,
                'stock' => 35,
                'thumbnail' => 'products/msi_spatium_m482.jpg',
                'is_featured' => true,
                'is_active' => true,
                'category_id' => $catLinhKien->id,
                'brand_id' => $brandMsi->id,
            ]
        );

        $p5->attributes()->delete();
        $p5->images()->delete();
        ProductAttribute::create(['product_id' => $p5->id, 'name' => 'Dung lượng', 'value' => '1TB']);
        ProductAttribute::create(['product_id' => $p5->id, 'name' => 'Chuẩn giao tiếp', 'value' => 'NVMe PCIe 4.0']);

        // Sản phẩm 6: VGA NVIDIA
        $p6 = Product::updateOrCreate(
            ['sku' => 'VGA-NVIDIA-4060'],
            [
                'name' => 'Card Màn Hình NVIDIA GeForce RTX 4060',
                'slug' => Str::slug('Card Màn Hình NVIDIA GeForce RTX 4060'),
                'summary' => 'Card đồ họa phổ biến cho game thủ và dựng phim tầm trung.',
                'description' => 'RTX 4060 mang lại hiệu năng ổn định cho game 1080p, hỗ trợ DLSS 3 và tiết kiệm điện năng.',
                'price' => 9990000,
                'sale_price' => 9490000,
                'stock' => 12,
                'thumbnail' => 'products/nvidia_rtx4060.jpg',
                'is_featured' => false,
                'is_active' => true,
                'category_id' => $catLinhKien->id,
                'brand_id' => $brandNvidia->id,
            ]
        );

        $p6->attributes()->delete();
        $p6->images()->delete();
        ProductAttribute::create(['product_id' => $p6->id, 'name' => 'VGA', 'value' => 'RTX 4060']);
        ProductAttribute::create(['product_id' => $p6->id, 'name' => 'Dung lượng VRAM', 'value' => '8GB']);
    }
}
