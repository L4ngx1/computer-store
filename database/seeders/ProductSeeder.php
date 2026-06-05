<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(CatalogSeeder::class);

        foreach ($this->productsByCategory() as $categorySlug => $names) {
            $category = Category::where('slug', $categorySlug)->first();

            if (! $category) {
                continue;
            }

            $activeSkus = [];

            foreach (array_values($names) as $index => $name) {
                $position = $index + 1;
                $brand = $this->brandFor($name);
                $sku = strtoupper(Str::slug($categorySlug . '-' . $position, '-'));
                $activeSkus[] = $sku;

                $product = Product::updateOrCreate(
                    ['sku' => $sku],
                    [
                        'name' => $name,
                        'slug' => $this->uniqueSlug($name, $sku),
                        'summary' => $this->summaryFor($categorySlug, $name),
                        'description' => $this->descriptionFor($categorySlug, $name),
                        'price' => $this->priceFor($categorySlug, $position),
                        'sale_price' => $position % 3 === 0 ? null : $this->salePriceFor($categorySlug, $position),
                        'stock' => 8 + ($position * 4) % 40,
                        'thumbnail' => "products/{$categorySlug}-{$position}.jpg",
                        'is_featured' => $position <= 2,
                        'is_active' => true,
                        'category_id' => $category->id,
                        'brand_id' => $brand->id,
                    ]
                );

                $this->syncDetails($product, $this->attributesFor($categorySlug, $name, $position), [
                    "products/{$categorySlug}-{$position}-1.jpg",
                    "products/{$categorySlug}-{$position}-2.jpg",
                ]);
            }

            $this->removeUnusedSeedProducts($category, $activeSkus);
        }
    }

    private function productsByCategory(): array
    {
        return [
            'pc-gaming' => ['ASUS ROG Strix G16CHR', 'MSI MAG Infinite S3', 'Acer Predator Orion 3000', 'Lenovo Legion Tower 5', 'HP OMEN 25L Gaming Desktop', 'Dell Alienware Aurora R16', 'Gigabyte AORUS Model S', 'Corsair Vengeance i7400', 'ASUS ROG G22CH', 'MSI MEG Trident X2'],
            'pc-van-phong' => ['Dell OptiPlex 7010 SFF', 'HP ProDesk 400 G9', 'Lenovo ThinkCentre Neo 50s', 'ASUS ExpertCenter D5 SFF', 'Acer Veriton X2690G', 'Dell Vostro 3020 Tower', 'HP Elite Mini 800 G9', 'Lenovo ThinkCentre M70q Gen 4', 'ASUS Mini PC PN64', 'Acer Aspire TC-1780'],
            'pc-workstation' => ['Dell Precision 3660 Tower', 'HP Z2 Tower G9', 'Lenovo ThinkStation P3 Tower', 'ASUS ProArt Station PD5', 'MSI Creator P100X', 'Dell Precision 5860 Tower', 'HP Z4 G5 Workstation', 'Lenovo ThinkStation P5', 'Apple Mac Studio M2 Max', 'ASUS ExpertCenter ProArt PA90'],
            'pc-dong-bo' => ['ASUS ROG Strix G10DK', 'Dell Inspiron Desktop 3030', 'HP Pavilion Desktop TP01', 'Lenovo IdeaCentre Gaming 5', 'Acer Aspire TC-1780', 'MSI PRO DP21', 'ASUS ExpertCenter D7 Tower', 'Dell XPS Desktop 8960', 'HP Envy Desktop TE02', 'Lenovo IdeaCentre 3 Desktop'],
            'laptop' => ['ASUS Vivobook 15 X1504ZA', 'Acer Aspire 5 A515', 'Dell Inspiron 15 3530', 'HP Pavilion 15-eg', 'Lenovo IdeaPad Slim 5', 'MSI Modern 14 C13M', 'ASUS Zenbook 14 OLED UX3405', 'Acer Swift Go 14', 'Dell XPS 13 9340', 'Apple MacBook Air 13 M3'],
            'laptop-gaming' => ['ASUS ROG Strix G16 G614', 'MSI Katana 15 B13V', 'Acer Nitro V 15', 'Lenovo Legion 5 16IRX9', 'HP Victus 16', 'Dell G15 5530', 'ASUS TUF Gaming A15', 'MSI Raider GE68 HX', 'Acer Predator Helios Neo 16', 'Lenovo LOQ 15IRX9'],
            'laptop-van-phong' => ['Dell Latitude 5440', 'HP ProBook 450 G10', 'Lenovo ThinkPad E14 Gen 5', 'ASUS ExpertBook B1 B1502', 'Acer TravelMate P2', 'Dell Vostro 3430', 'HP EliteBook 840 G10', 'Lenovo ThinkBook 14 G6', 'ASUS Vivobook Go 15', 'Acer Aspire Lite 15'],
            'laptop-do-hoa' => ['ASUS ProArt Studiobook 16 OLED', 'MSI Creator Z16 HX Studio', 'Dell Precision 5680', 'HP ZBook Studio G10', 'Lenovo ThinkPad P1 Gen 6', 'Apple MacBook Pro 14 M3 Pro', 'Acer ConceptD 5', 'ASUS Zenbook Pro 16X OLED', 'MSI Stealth 16 Studio', 'Dell XPS 16 9640'],
            'linh-kien' => ['Intel Core i5-13400F', 'AMD Ryzen 5 7600', 'ASUS PRIME B760M-A WIFI', 'MSI B650M Gaming Plus WIFI', 'Kingston Fury Beast 16GB DDR5', 'Samsung 990 PRO 1TB', 'Corsair RM750e 750W', 'Cooler Master MasterBox TD500 Mesh', 'DeepCool AK400 Digital', 'Gigabyte GeForce RTX 4060 Eagle OC'],
            'cpu' => ['Intel Core i3-12100F', 'Intel Core i5-12400F', 'Intel Core i5-13400F', 'Intel Core i7-13700K', 'Intel Core i9-14900K', 'AMD Ryzen 5 5600', 'AMD Ryzen 5 7600', 'AMD Ryzen 7 7700', 'AMD Ryzen 7 7800X3D', 'AMD Ryzen 9 7950X'],
            'mainboard' => ['ASUS PRIME B760M-A WIFI D4', 'ASUS TUF Gaming B760-PLUS WIFI', 'MSI PRO B760M-A WIFI DDR4', 'MSI MAG B650 Tomahawk WIFI', 'Gigabyte B760M DS3H DDR4', 'Gigabyte B650 AORUS Elite AX', 'ASUS ROG Strix B650E-F Gaming WIFI', 'MSI MPG Z790 Edge WIFI', 'Gigabyte Z790 AORUS Elite AX', 'ASUS ROG Maximus Z790 Hero'],
            'ram' => ['Kingston Fury Beast 16GB DDR4 3200', 'Kingston Fury Beast 32GB DDR5 5600', 'Corsair Vengeance LPX 16GB DDR4 3200', 'Corsair Vengeance RGB 32GB DDR5 6000', 'G.Skill Trident Z5 RGB 32GB DDR5 6000', 'G.Skill Ripjaws V 16GB DDR4 3600', 'Crucial Pro 32GB DDR5 5600', 'Kingston Fury Renegade RGB 32GB DDR5 6400', 'Corsair Dominator Platinum RGB 32GB DDR5', 'G.Skill Flare X5 32GB DDR5 6000'],
            'vga' => ['ASUS Dual GeForce RTX 4060 OC', 'MSI GeForce RTX 4070 Ti GAMING X SLIM 12G', 'Gigabyte GeForce RTX 4060 Eagle OC', 'ASUS TUF Gaming GeForce RTX 4070 SUPER', 'MSI GeForce RTX 4060 Ventus 2X Black', 'Gigabyte Radeon RX 7600 Gaming OC', 'ASUS Dual Radeon RX 7600 OC', 'MSI Radeon RX 6750 XT MECH 2X', 'Gigabyte GeForce RTX 4080 SUPER Gaming OC', 'ASUS ROG Strix GeForce RTX 4090 OC'],
            'ssd-hdd' => ['Samsung 990 PRO 1TB', 'Samsung 980 PRO 1TB', 'WD Black SN850X 1TB', 'WD Blue SN580 1TB', 'Kingston NV2 1TB', 'Kingston KC3000 2TB', 'Crucial P3 Plus 1TB', 'MSI Spatium M482 1TB', 'Seagate BarraCuda 2TB HDD', 'WD Blue 4TB HDD'],
            'psu' => ['Corsair RM750e 750W', 'Corsair RM850x 850W', 'Cooler Master MWE Gold 750 V2', 'Cooler Master V850 Gold i', 'MSI MAG A650BN 650W', 'MSI MPG A850G PCIE5', 'ASUS TUF Gaming 750W Bronze', 'ASUS ROG Thor 1000W Platinum II', 'Thermaltake Toughpower GF A3 850W', 'DeepCool PK650D 650W'],
            'case-may-tinh' => ['Cooler Master MasterBox TD500 Mesh V2', 'NZXT H5 Flow', 'NZXT H7 Flow', 'Corsair 4000D Airflow', 'Corsair iCUE 5000X RGB', 'DeepCool CH560 Digital', 'Thermaltake View 200 TG ARGB', 'ASUS TUF Gaming GT301', 'MSI MAG Forge 320R Airflow', 'Gigabyte C301 Glass'],
            'tan-nhiet' => ['DeepCool AK400 Digital', 'DeepCool AK620 Digital', 'Cooler Master Hyper 212 Halo', 'Cooler Master MasterLiquid ML240L V2 RGB', 'Corsair iCUE H100i Elite Capellix XT', 'Corsair iCUE H150i Elite LCD XT', 'NZXT Kraken 240 RGB', 'NZXT Kraken Elite 360 RGB', 'ASUS ROG Ryujin III 360', 'Thermaltake TH360 V2 ARGB'],
            'man-hinh' => ['LG UltraGear 24GN60R-B', 'LG UltraGear 27GP850-B', 'Samsung Odyssey G5 G55C 27', 'Samsung Odyssey G7 32', 'ASUS TUF Gaming VG249Q3A', 'ASUS ProArt PA278QV', 'MSI G274QPF-QD', 'AOC 24G2SP', 'BenQ GW2480', 'ViewSonic VX2758A-2K-PRO'],
            'ban-phim' => ['Logitech G Pro X TKL Lightspeed', 'Logitech MX Keys S', 'Razer BlackWidow V4', 'Razer Huntsman Mini', 'Corsair K70 RGB Pro', 'SteelSeries Apex Pro TKL', 'HyperX Alloy Origins Core', 'ASUS ROG Strix Scope II 96 Wireless', 'MSI Vigor GK50 Elite', 'Rapoo V500Pro'],
            'chuot' => ['Logitech G Pro X Superlight 2', 'Logitech MX Master 3S', 'Razer DeathAdder V3 Pro', 'Razer Viper V3 Pro', 'SteelSeries Aerox 5 Wireless', 'HyperX Pulsefire Haste 2', 'Corsair M65 RGB Ultra', 'ASUS ROG Harpe Ace Aim Lab Edition', 'MSI Clutch GM31 Lightweight', 'Rapoo MT760'],
            'tai-nghe' => ['Logitech G Pro X 2 Lightspeed', 'Razer BlackShark V2 Pro', 'SteelSeries Arctis Nova 7', 'HyperX Cloud III Wireless', 'Corsair HS80 RGB Wireless', 'ASUS ROG Delta S', 'MSI Immerse GH50', 'Apple AirPods Max', 'Samsung Galaxy Buds2 Pro', 'Sony WH-1000XM5'],
            'loa' => ['Logitech Z407 Bluetooth Speakers', 'Logitech Z625 Speaker System', 'Razer Nommo V2', 'SteelSeries Arena 3', 'Creative Pebble V3', 'Edifier R1280DBs', 'Samsung HW-C450 Soundbar', 'LG XBOOM Go XG5Q', 'Anker Soundcore Motion Plus', 'JBL Quantum Duo'],
            'webcam' => ['Logitech Brio 4K', 'Logitech C920s HD Pro', 'Razer Kiyo Pro', 'ASUS ROG Eye S', 'AverMedia Live Streamer CAM 313', 'Microsoft Modern Webcam', 'HP 960 4K Streaming Webcam', 'Dell UltraSharp WB7022', 'Rapoo C260 Full HD', 'Lenovo 300 FHD Webcam'],
            'thiet-bi-mang' => ['TP-Link Archer AX55', 'TP-Link Archer AX73', 'TP-Link Deco X50 Mesh WiFi 6', 'TP-Link Deco BE65 WiFi 7', 'ASUS RT-AX58U', 'ASUS TUF Gaming AX5400', 'Ugreen USB-C Gigabit Ethernet Adapter', 'TP-Link TL-SG108E Switch', 'TP-Link Archer T4U Plus', 'ASUS PCE-AX3000 WiFi 6 Adapter'],
            'phu-kien-laptop' => ['Ugreen Laptop Stand LP451', 'Logitech Pebble Keys 2 K380s', 'Logitech M350s Pebble Mouse 2', 'Anker PowerExpand 8-in-1 USB-C Hub', 'Ugreen 9-in-1 USB-C Docking Station', 'Cooler Master Notepal X150R', 'ASUS ROG Ranger BP2701 Backpack', 'Tomtoc 360 Protective Laptop Sleeve', 'Anker 737 Power Bank', 'Rapoo XC350 Wireless Charging Pad'],
            'cap-adapter' => ['Ugreen USB-C to HDMI Cable', 'Ugreen USB-C 100W Cable', 'Anker PowerLine III USB-C Cable', 'Anker 543 USB-C to USB-C Cable', 'Apple USB-C Digital AV Multiport Adapter', 'Samsung 45W USB-C Power Adapter', 'Ugreen 65W GaN Charger', 'TP-Link UE306 USB Ethernet Adapter', 'Ugreen HDMI 2.1 Cable 8K', 'Anker 332 USB-C Hub'],
            'ghe-gaming' => ['Corsair TC100 Relaxed Gaming Chair', 'Corsair T3 Rush Gaming Chair', 'Razer Iskur V2', 'Razer Enki Gaming Chair', 'Cooler Master Caliber R3', 'Cooler Master Caliber X2', 'MSI MAG CH120 X', 'ASUS ROG Chariot Core', 'Thermaltake Argent E700', 'AndaSeat Kaiser 3'],
        ];
    }

    private function brandFor(string $name): Brand
    {
        $aliases = [
            'western digital' => 'western-digital',
            'wd ' => 'western-digital',
            'g.skill' => 'gskill',
            'tp-link' => 'tp-link',
            'cooler master' => 'cooler-master',
            'deepcool' => 'deepcool',
            'thermaltake' => 'thermaltake',
            'steelseries' => 'steelseries',
            'viewsonic' => 'viewsonic',
            'gigabyte' => 'gigabyte',
            'kingston' => 'kingston',
            'corsair' => 'corsair',
            'crucial' => 'crucial',
            'logitech' => 'logitech',
            'samsung' => 'samsung',
            'seagate' => 'seagate',
            'philips' => 'philips',
            'lenovo' => 'lenovo',
            'nvidia' => 'nvidia',
            'ugreen' => 'ugreen',
            'anker' => 'anker',
            'razer' => 'razer',
            'hyperx' => 'hyperx',
            'rapoo' => 'rapoo',
            'intel' => 'intel',
            'apple' => 'apple',
            'asus' => 'asus',
            'acer' => 'acer',
            'dell' => 'dell',
            'msi' => 'msi',
            'amd' => 'amd',
            'benq' => 'benq',
            'aoc' => 'aoc',
            'nzxt' => 'nzxt',
            'lg' => 'lg',
            'hp' => 'hp',
        ];

        $normalized = Str::lower($name);

        foreach ($aliases as $needle => $slug) {
            if (str_contains($normalized, $needle)) {
                return Brand::where('slug', $slug)->firstOrFail();
            }
        }

        return Brand::where('slug', 'asus')->firstOrFail();
    }

    private function priceFor(string $categorySlug, int $position): int
    {
        [$base, $step] = $this->priceRanges()[$categorySlug] ?? [990000, 180000];

        return $base + $step * ($position - 1);
    }

    private function salePriceFor(string $categorySlug, int $position): int
    {
        $price = $this->priceFor($categorySlug, $position);

        return max(100000, $price - max(50000, (int) ($price * 0.06)));
    }

    private function priceRanges(): array
    {
        return [
            'pc-gaming' => [18990000, 2200000],
            'pc-van-phong' => [7490000, 700000],
            'pc-workstation' => [28990000, 4200000],
            'pc-dong-bo' => [9990000, 1200000],
            'laptop' => [11990000, 950000],
            'laptop-gaming' => [19990000, 1800000],
            'laptop-van-phong' => [9990000, 850000],
            'laptop-do-hoa' => [24990000, 2600000],
            'linh-kien' => [990000, 800000],
            'cpu' => [2190000, 1200000],
            'mainboard' => [1890000, 720000],
            'ram' => [690000, 320000],
            'vga' => [6990000, 2600000],
            'ssd-hdd' => [890000, 450000],
            'psu' => [890000, 350000],
            'case-may-tinh' => [790000, 280000],
            'tan-nhiet' => [490000, 420000],
            'man-hinh' => [2290000, 650000],
            'ban-phim' => [390000, 260000],
            'chuot' => [250000, 220000],
            'tai-nghe' => [590000, 420000],
            'loa' => [490000, 390000],
            'webcam' => [450000, 350000],
            'thiet-bi-mang' => [490000, 320000],
            'phu-kien-laptop' => [190000, 180000],
            'cap-adapter' => [120000, 90000],
            'ghe-gaming' => [2490000, 650000],
        ];
    }

    private function summaryFor(string $categorySlug, string $name): string
    {
        return "{$name} chính hãng, phù hợp cho nhu cầu " . $this->useCaseFor($categorySlug) . '.';
    }

    private function descriptionFor(string $categorySlug, string $name): string
    {
        return "{$name} là sản phẩm thật thuộc nhóm {$this->useCaseFor($categorySlug)}, được seed để hiển thị dữ liệu cửa hàng máy tính đầy đủ hơn. Sản phẩm có thông số tham khảo, tồn kho mẫu và ảnh theo đúng đường dẫn storage.";
    }

    private function useCaseFor(string $categorySlug): string
    {
        return [
            'pc-gaming' => 'chơi game',
            'pc-van-phong' => 'làm việc văn phòng',
            'pc-workstation' => 'thiết kế, render và dựng phim',
            'pc-dong-bo' => 'học tập và làm việc',
            'laptop' => 'di động hằng ngày',
            'laptop-gaming' => 'gaming hiệu năng cao',
            'laptop-van-phong' => 'văn phòng',
            'laptop-do-hoa' => 'đồ họa sáng tạo',
            'linh-kien' => 'nâng cấp PC',
            'cpu' => 'xử lý hiệu năng',
            'mainboard' => 'lắp ráp hệ thống',
            'ram' => 'nâng cấp bộ nhớ',
            'vga' => 'đồ họa và gaming',
            'ssd-hdd' => 'lưu trữ dữ liệu',
            'psu' => 'cấp nguồn ổn định',
            'case-may-tinh' => 'lắp ráp PC',
            'tan-nhiet' => 'làm mát hệ thống',
            'man-hinh' => 'hiển thị',
            'ban-phim' => 'gõ phím',
            'chuot' => 'điều khiển',
            'tai-nghe' => 'âm thanh cá nhân',
            'loa' => 'giải trí âm thanh',
            'webcam' => 'họp và livestream',
            'thiet-bi-mang' => 'kết nối mạng',
            'phu-kien-laptop' => 'phụ kiện laptop',
            'cap-adapter' => 'kết nối thiết bị',
            'ghe-gaming' => 'ngồi làm việc và chơi game',
        ][$categorySlug] ?? 'sử dụng hằng ngày';
    }

    private function attributesFor(string $categorySlug, string $name, int $position): array
    {
        if (str_contains($categorySlug, 'laptop')) {
            return ['CPU' => $position % 2 ? 'Intel Core i5/i7' : 'AMD Ryzen 5/7', 'RAM' => $position <= 5 ? '16GB' : '32GB', 'Ổ cứng' => '512GB - 1TB SSD', 'Màn hình' => $position % 2 ? '14-15.6 inch' : '16 inch'];
        }

        if (str_starts_with($categorySlug, 'pc')) {
            return ['CPU' => $position % 2 ? 'Intel Core' : 'AMD Ryzen', 'RAM' => $position <= 5 ? '16GB' : '32GB', 'Ổ cứng' => '512GB - 1TB SSD', 'VGA' => str_contains(Str::lower($name), 'gaming') || $position % 2 === 0 ? 'NVIDIA GeForce / AMD Radeon' : 'Tích hợp hoặc rời'];
        }

        return match ($categorySlug) {
            'cpu' => ['Dòng CPU' => str_contains($name, 'AMD') ? 'AMD Ryzen' : 'Intel Core', 'Bảo hành' => '36 tháng', 'Tình trạng' => 'Mới 100%'],
            'mainboard' => ['Kích thước' => str_contains(Str::lower($name), 'm') ? 'mATX' : 'ATX', 'Bảo hành' => '36 tháng', 'Tình trạng' => 'Mới 100%'],
            'ram' => ['Dung lượng' => str_contains($name, '32GB') ? '32GB' : '16GB', 'Chuẩn RAM' => str_contains($name, 'DDR5') ? 'DDR5' : 'DDR4', 'Bảo hành' => '36 tháng'],
            'vga' => ['Bộ nhớ' => '8GB - 24GB', 'Chuẩn bộ nhớ' => 'GDDR6/GDDR6X', 'Bảo hành' => '36 tháng'],
            'ssd-hdd' => ['Dung lượng' => str_contains($name, '2TB') ? '2TB' : (str_contains($name, '4TB') ? '4TB' : '1TB'), 'Chuẩn' => str_contains(Str::lower($name), 'hdd') || str_contains($name, 'BarraCuda') ? 'HDD SATA' : 'SSD NVMe', 'Bảo hành' => '36 tháng'],
            'psu' => ['Công suất' => '650W - 1000W', 'Chuẩn hiệu suất' => '80 Plus', 'Bảo hành' => '36 tháng'],
            'man-hinh' => ['Kích thước' => '24 - 32 inch', 'Độ phân giải' => 'FHD/QHD/4K', 'Tấm nền' => 'IPS/VA/OLED'],
            default => ['Bảo hành' => '12 tháng', 'Tình trạng' => 'Mới 100%', 'Nguồn gốc' => 'Chính hãng'],
        };
    }

    private function syncDetails(Product $product, array $attributes, array $images): void
    {
        $product->attributes()->delete();
        $product->images()->delete();

        foreach ($attributes as $name => $value) {
            ProductAttribute::create(['product_id' => $product->id, 'name' => $name, 'value' => $value]);
        }

        foreach ($images as $image) {
            ProductImage::create(['product_id' => $product->id, 'image_path' => $image]);
        }
    }

    private function uniqueSlug(string $name, string $sku): string
    {
        return Str::slug($name . '-' . Str::lower($sku));
    }

    private function removeUnusedSeedProducts(Category $category, array $activeSkus): void
    {
        Product::where('category_id', $category->id)
            ->whereNotIn('sku', $activeSkus)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('order_items')
                    ->whereColumn('order_items.product_id', 'products.id');
            })
            ->delete();
    }
}
