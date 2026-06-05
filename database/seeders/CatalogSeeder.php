<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->categories() as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                [
                    'name' => $category['name'],
                    'image' => $category['image'],
                    'is_active' => true,
                ]
            );
        }

        foreach ($this->brands() as $brand) {
            Brand::updateOrCreate(
                ['slug' => $brand['slug']],
                [
                    'name' => $brand['name'],
                    'logo' => $brand['logo'],
                ]
            );
        }
    }

    private function categories(): array
    {
        return [
            ['name' => 'PC Gaming', 'slug' => 'pc-gaming', 'image' => 'categories/pc-gaming.jpg'],
            ['name' => 'PC Văn Phòng', 'slug' => 'pc-van-phong', 'image' => 'categories/pc-van-phong.jpg'],
            ['name' => 'PC Workstation', 'slug' => 'pc-workstation', 'image' => 'categories/pc-workstation.jpg'],
            ['name' => 'PC Đồng Bộ', 'slug' => 'pc-dong-bo', 'image' => 'categories/pc-dong-bo.jpg'],
            ['name' => 'Laptop', 'slug' => 'laptop', 'image' => 'categories/laptop.jpg'],
            ['name' => 'Laptop Gaming', 'slug' => 'laptop-gaming', 'image' => 'categories/laptop-gaming.jpg'],
            ['name' => 'Laptop Văn Phòng', 'slug' => 'laptop-van-phong', 'image' => 'categories/laptop-van-phong.jpg'],
            ['name' => 'Laptop Đồ Họa', 'slug' => 'laptop-do-hoa', 'image' => 'categories/laptop-do-hoa.jpg'],
            ['name' => 'Linh Kiện Máy Tính', 'slug' => 'linh-kien', 'image' => 'categories/linh-kien.jpg'],
            ['name' => 'CPU - Bộ Vi Xử Lý', 'slug' => 'cpu', 'image' => 'categories/cpu.jpg'],
            ['name' => 'Mainboard - Bo Mạch Chủ', 'slug' => 'mainboard', 'image' => 'categories/mainboard.jpg'],
            ['name' => 'RAM Máy Tính', 'slug' => 'ram', 'image' => 'categories/ram.jpg'],
            ['name' => 'VGA - Card Màn Hình', 'slug' => 'vga', 'image' => 'categories/vga.jpg'],
            ['name' => 'SSD - HDD', 'slug' => 'ssd-hdd', 'image' => 'categories/ssd-hdd.jpg'],
            ['name' => 'Nguồn Máy Tính', 'slug' => 'psu', 'image' => 'categories/psu.jpg'],
            ['name' => 'Case Máy Tính', 'slug' => 'case-may-tinh', 'image' => 'categories/case-may-tinh.jpg'],
            ['name' => 'Tản Nhiệt', 'slug' => 'tan-nhiet', 'image' => 'categories/tan-nhiet.jpg'],
            ['name' => 'Màn Hình Máy Tính', 'slug' => 'man-hinh', 'image' => 'categories/man-hinh.jpg'],
            ['name' => 'Bàn Phím', 'slug' => 'ban-phim', 'image' => 'categories/ban-phim.jpg'],
            ['name' => 'Chuột Máy Tính', 'slug' => 'chuot', 'image' => 'categories/chuot.jpg'],
            ['name' => 'Tai Nghe', 'slug' => 'tai-nghe', 'image' => 'categories/tai-nghe.jpg'],
            ['name' => 'Loa Máy Tính', 'slug' => 'loa', 'image' => 'categories/loa.jpg'],
            ['name' => 'Webcam', 'slug' => 'webcam', 'image' => 'categories/webcam.jpg'],
            ['name' => 'Thiết Bị Mạng', 'slug' => 'thiet-bi-mang', 'image' => 'categories/thiet-bi-mang.jpg'],
            ['name' => 'Phụ Kiện Laptop', 'slug' => 'phu-kien-laptop', 'image' => 'categories/phu-kien-laptop.jpg'],
            ['name' => 'Cáp - Adapter', 'slug' => 'cap-adapter', 'image' => 'categories/cap-adapter.jpg'],
            ['name' => 'Ghế Gaming', 'slug' => 'ghe-gaming', 'image' => 'categories/ghe-gaming.jpg'],
        ];
    }

    private function brands(): array
    {
        return [
            ['name' => 'ASUS', 'slug' => 'asus', 'logo' => 'brands/asus.png'],
            ['name' => 'MSI', 'slug' => 'msi', 'logo' => 'brands/msi.png'],
            ['name' => 'Gigabyte', 'slug' => 'gigabyte', 'logo' => 'brands/gigabyte.png'],
            ['name' => 'Acer', 'slug' => 'acer', 'logo' => 'brands/acer.png'],
            ['name' => 'Dell', 'slug' => 'dell', 'logo' => 'brands/dell.png'],
            ['name' => 'HP', 'slug' => 'hp', 'logo' => 'brands/hp.png'],
            ['name' => 'Lenovo', 'slug' => 'lenovo', 'logo' => 'brands/lenovo.png'],
            ['name' => 'Apple', 'slug' => 'apple', 'logo' => 'brands/apple.png'],
            ['name' => 'LG', 'slug' => 'lg', 'logo' => 'brands/lg.png'],
            ['name' => 'Samsung', 'slug' => 'samsung', 'logo' => 'brands/samsung.png'],
            ['name' => 'Intel', 'slug' => 'intel', 'logo' => 'brands/intel.png'],
            ['name' => 'AMD', 'slug' => 'amd', 'logo' => 'brands/amd.png'],
            ['name' => 'NVIDIA', 'slug' => 'nvidia', 'logo' => 'brands/nvidia.png'],
            ['name' => 'Kingston', 'slug' => 'kingston', 'logo' => 'brands/kingston.png'],
            ['name' => 'Corsair', 'slug' => 'corsair', 'logo' => 'brands/corsair.png'],
            ['name' => 'G.Skill', 'slug' => 'gskill', 'logo' => 'brands/gskill.png'],
            ['name' => 'Crucial', 'slug' => 'crucial', 'logo' => 'brands/crucial.png'],
            ['name' => 'Western Digital', 'slug' => 'western-digital', 'logo' => 'brands/western-digital.png'],
            ['name' => 'Seagate', 'slug' => 'seagate', 'logo' => 'brands/seagate.png'],
            ['name' => 'Cooler Master', 'slug' => 'cooler-master', 'logo' => 'brands/cooler-master.png'],
            ['name' => 'DeepCool', 'slug' => 'deepcool', 'logo' => 'brands/deepcool.png'],
            ['name' => 'NZXT', 'slug' => 'nzxt', 'logo' => 'brands/nzxt.png'],
            ['name' => 'Thermaltake', 'slug' => 'thermaltake', 'logo' => 'brands/thermaltake.png'],
            ['name' => 'Logitech', 'slug' => 'logitech', 'logo' => 'brands/logitech.png'],
            ['name' => 'Razer', 'slug' => 'razer', 'logo' => 'brands/razer.png'],
            ['name' => 'SteelSeries', 'slug' => 'steelseries', 'logo' => 'brands/steelseries.png'],
            ['name' => 'HyperX', 'slug' => 'hyperx', 'logo' => 'brands/hyperx.png'],
            ['name' => 'Rapoo', 'slug' => 'rapoo', 'logo' => 'brands/rapoo.png'],
            ['name' => 'TP-Link', 'slug' => 'tp-link', 'logo' => 'brands/tp-link.png'],
            ['name' => 'Ugreen', 'slug' => 'ugreen', 'logo' => 'brands/ugreen.png'],
            ['name' => 'Anker', 'slug' => 'anker', 'logo' => 'brands/anker.png'],
            ['name' => 'ViewSonic', 'slug' => 'viewsonic', 'logo' => 'brands/viewsonic.png'],
            ['name' => 'AOC', 'slug' => 'aoc', 'logo' => 'brands/aoc.png'],
            ['name' => 'BenQ', 'slug' => 'benq', 'logo' => 'brands/benq.png'],
            ['name' => 'Philips', 'slug' => 'philips', 'logo' => 'brands/philips.png'],
        ];
    }
}
