<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    private const PRODUCTS_PER_CATEGORY = 12;

    public function run(): void
    {
        $this->call(CatalogSeeder::class);

        $brands = Brand::orderBy('id')->get();

        Category::where('is_active', true)->orderBy('id')->get()->each(function (Category $category) use ($brands) {
            for ($index = 1; $index <= self::PRODUCTS_PER_CATEGORY; $index++) {
                $brand = $brands[($category->id + $index) % $brands->count()];
                $data = $this->productData($category, $brand, $index);

                $product = Product::updateOrCreate(
                    ['sku' => $data['sku']],
                    [
                        'name' => $data['name'],
                        'slug' => Str::slug($data['name']),
                        'summary' => $data['summary'],
                        'description' => $data['description'],
                        'price' => $data['price'],
                        'sale_price' => $data['sale_price'],
                        'stock' => $data['stock'],
                        'thumbnail' => $data['thumbnail'],
                        'is_featured' => $index <= 3,
                        'is_active' => true,
                        'category_id' => $category->id,
                        'brand_id' => $brand->id,
                    ]
                );

                $this->syncDetails($product, $data['attributes'], $data['images']);
            }
        });
    }

    private function productData(Category $category, Brand $brand, int $index): array
    {
        $profile = $this->categoryProfiles()[$category->slug] ?? $this->defaultProfile($category);
        $variant = $profile['variants'][($index - 1) % count($profile['variants'])];
        $price = $profile['price_min'] + ($index * $profile['price_step']);
        $hasSale = $index % 3 !== 0;

        $name = "{$brand->name} {$variant} {$profile['suffix']} {$index}";

        return [
            'sku' => strtoupper(Str::slug($category->slug . '-' . $brand->slug . '-' . $index, '-')),
            'name' => $name,
            'summary' => "{$name} phù hợp cho nhu cầu {$profile['use_case']}.",
            'description' => "{$name} được lựa chọn cho website máy tính với cấu hình ổn định, bảo hành rõ ràng và dễ nâng cấp theo nhu cầu sử dụng.",
            'price' => $price,
            'sale_price' => $hasSale ? max(100000, $price - ($profile['discount'] + $index * 10000)) : null,
            'stock' => 5 + ($index * 3) % 46,
            'thumbnail' => "products/{$category->slug}-{$index}.jpg",
            'images' => [
                "products/{$category->slug}-{$index}-1.jpg",
                "products/{$category->slug}-{$index}-2.jpg",
            ],
            'attributes' => $this->attributesFor($category->slug, $index),
        ];
    }

    private function syncDetails(Product $product, array $attributes, array $images): void
    {
        $product->attributes()->delete();
        $product->images()->delete();

        foreach ($attributes as $name => $value) {
            ProductAttribute::create([
                'product_id' => $product->id,
                'name' => $name,
                'value' => $value,
            ]);
        }

        foreach ($images as $image) {
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $image,
            ]);
        }
    }

    private function categoryProfiles(): array
    {
        return [
            'pc-gaming' => $this->profile(['Aero', 'Phantom', 'Storm', 'Titan'], 'Gaming PC', 'chơi game', 15990000, 1450000, 800000),
            'pc-van-phong' => $this->profile(['Office', 'Slim', 'Mini', 'Eco'], 'Desktop', 'văn phòng', 6990000, 550000, 300000),
            'pc-workstation' => $this->profile(['Creator', 'Studio', 'Render', 'Pro'], 'Workstation', 'đồ họa và dựng phim', 28990000, 2400000, 1200000),
            'pc-dong-bo' => $this->profile(['Essential', 'Tower', 'Business', 'Compact'], 'PC', 'học tập và làm việc', 8990000, 800000, 400000),
            'laptop' => $this->profile(['Modern', 'Aspire', 'IdeaPad', 'Vivobook'], 'Laptop', 'học tập và làm việc', 11990000, 900000, 500000),
            'laptop-gaming' => $this->profile(['ROG', 'Katana', 'Nitro', 'Legion'], 'Gaming Laptop', 'chơi game', 19990000, 1600000, 900000),
            'laptop-van-phong' => $this->profile(['Swift', 'Pavilion', 'ThinkBook', 'Inspiron'], 'Office Laptop', 'văn phòng', 10990000, 750000, 450000),
            'laptop-do-hoa' => $this->profile(['Studio', 'Creator', 'ProArt', 'Precision'], 'Creator Laptop', 'thiết kế đồ họa', 22990000, 1900000, 1000000),
            'linh-kien' => $this->profile(['Upgrade', 'Performance', 'Prime', 'Gaming'], 'Component', 'nâng cấp máy tính', 990000, 350000, 120000),
            'cpu' => $this->profile(['Core', 'Ryzen', 'Ultra', 'Thread'], 'Processor', 'xử lý đa nhiệm', 2490000, 600000, 200000),
            'mainboard' => $this->profile(['Prime', 'Gaming', 'Aorus', 'Mortar'], 'Mainboard', 'lắp ráp PC', 2190000, 420000, 180000),
            'ram' => $this->profile(['Fury', 'Vengeance', 'Trident', 'Value'], 'RAM DDR4/DDR5', 'nâng cấp bộ nhớ', 790000, 180000, 70000),
            'vga' => $this->profile(['GeForce', 'Radeon', 'Gaming X', 'Eagle'], 'Graphics Card', 'chơi game và render', 4990000, 1200000, 500000),
            'ssd-hdd' => $this->profile(['Blue', 'Black', 'Barracuda', 'Spatium'], 'Storage', 'lưu trữ tốc độ cao', 890000, 230000, 90000),
            'psu' => $this->profile(['Bronze', 'Gold', 'Platinum', 'Modular'], 'Power Supply', 'cấp nguồn ổn định', 990000, 250000, 90000),
            'case-may-tinh' => $this->profile(['Airflow', 'Mesh', 'RGB', 'Silent'], 'Case', 'lắp ráp PC', 790000, 220000, 80000),
            'tan-nhiet' => $this->profile(['Air', 'Liquid', 'Tower', 'ARGB'], 'Cooler', 'tản nhiệt CPU', 490000, 190000, 60000),
            'man-hinh' => $this->profile(['UltraGear', 'Odyssey', 'ProArt', 'Gaming'], 'Monitor', 'hiển thị sắc nét', 2490000, 650000, 250000),
            'ban-phim' => $this->profile(['Mechanical', 'Wireless', 'RGB', 'Office'], 'Keyboard', 'gõ phím và chơi game', 390000, 160000, 50000),
            'chuot' => $this->profile(['Wireless', 'Gaming', 'Ergo', 'Silent'], 'Mouse', 'điều khiển chính xác', 250000, 120000, 40000),
            'tai-nghe' => $this->profile(['Surround', 'Wireless', 'Studio', 'Gaming'], 'Headset', 'nghe gọi và chơi game', 490000, 170000, 60000),
            'loa' => $this->profile(['Stereo', 'Soundbar', 'Bluetooth', 'RGB'], 'Speaker', 'giải trí', 390000, 160000, 50000),
            'webcam' => $this->profile(['Full HD', 'Streaming', 'Business', 'Auto Focus'], 'Webcam', 'họp trực tuyến', 450000, 140000, 50000),
            'thiet-bi-mang' => $this->profile(['WiFi 6', 'Mesh', 'Router', 'Switch'], 'Network Device', 'kết nối mạng', 590000, 180000, 60000),
            'phu-kien-laptop' => $this->profile(['Stand', 'Sleeve', 'Cooling Pad', 'Dock'], 'Laptop Accessory', 'phụ kiện laptop', 190000, 90000, 30000),
            'cap-adapter' => $this->profile(['USB-C', 'HDMI', 'Hub', 'Charger'], 'Adapter', 'kết nối thiết bị', 150000, 70000, 20000),
            'ghe-gaming' => $this->profile(['Racing', 'Ergo', 'Premium', 'Fabric'], 'Gaming Chair', 'ngồi làm việc và chơi game', 2490000, 450000, 180000),
        ];
    }

    private function profile(array $variants, string $suffix, string $useCase, int $priceMin, int $priceStep, int $discount): array
    {
        return compact('variants', 'suffix', 'useCase') + [
            'use_case' => $useCase,
            'price_min' => $priceMin,
            'price_step' => $priceStep,
            'discount' => $discount,
        ];
    }

    private function defaultProfile(Category $category): array
    {
        return $this->profile(['Standard', 'Plus', 'Pro', 'Max'], $category->name, 'sử dụng hằng ngày', 990000, 250000, 100000);
    }

    private function attributesFor(string $categorySlug, int $index): array
    {
        if (str_contains($categorySlug, 'laptop')) {
            return [
                'CPU' => $index % 2 ? 'Intel Core i5' : 'AMD Ryzen 5',
                'RAM' => $index % 3 ? '16GB' : '32GB',
                'Ổ cứng' => $index % 2 ? '512GB SSD' : '1TB SSD',
                'Màn hình' => $index % 2 ? '15.6 inch FHD' : '16 inch 2.5K',
            ];
        }

        if (str_starts_with($categorySlug, 'pc')) {
            return [
                'CPU' => $index % 2 ? 'Intel Core i5' : 'AMD Ryzen 7',
                'RAM' => $index % 3 ? '16GB' : '32GB',
                'VGA' => $index % 2 ? 'RTX 4060' : 'Radeon RX 7600',
                'Ổ cứng' => '1TB SSD',
            ];
        }

        return match ($categorySlug) {
            'cpu' => ['Socket' => $index % 2 ? 'LGA1700' : 'AM5', 'Số nhân' => 6 + $index % 10, 'Bảo hành' => '36 tháng'],
            'mainboard' => ['Chipset' => $index % 2 ? 'B760' : 'B650', 'Kích thước' => $index % 2 ? 'ATX' : 'mATX', 'Bảo hành' => '36 tháng'],
            'ram' => ['Dung lượng' => $index % 2 ? '16GB' : '32GB', 'Bus' => $index % 2 ? '3200MHz' : '5600MHz', 'Chuẩn' => $index % 2 ? 'DDR4' : 'DDR5'],
            'vga' => ['VRAM' => $index % 2 ? '8GB' : '12GB', 'Chuẩn bộ nhớ' => 'GDDR6', 'Bảo hành' => '36 tháng'],
            'ssd-hdd' => ['Dung lượng' => $index % 2 ? '1TB' : '2TB', 'Chuẩn' => $index % 2 ? 'NVMe PCIe' : 'SATA', 'Bảo hành' => '36 tháng'],
            'psu' => ['Công suất' => 550 + $index * 50 . 'W', 'Chuẩn' => $index % 2 ? '80 Plus Bronze' : '80 Plus Gold', 'Bảo hành' => '36 tháng'],
            'man-hinh' => ['Kích thước' => $index % 2 ? '24 inch' : '27 inch', 'Tần số quét' => $index % 2 ? '75Hz' : '165Hz', 'Tấm nền' => 'IPS'],
            default => ['Bảo hành' => '12 tháng', 'Tình trạng' => 'Mới 100%', 'Nguồn gốc' => 'Chính hãng'],
        };
    }
}
