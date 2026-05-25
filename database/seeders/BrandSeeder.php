<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Thương hiệu BTCK - Thành viên 2
        Brand::updateOrCreate(
            ['slug' => 'btck-tech'],
            [
                'name' => 'BTCK Tech Store',
                'logo' => 'logo_btck.png'
            ]
        );

        // Các thương hiệu khác
        Brand::updateOrCreate(
            ['slug' => 'asus'],
            ['name' => 'ASUS', 'logo' => 'logo_asus.png']
        );

        Brand::updateOrCreate(
            ['slug' => 'msi'],
            ['name' => 'MSI', 'logo' => 'logo_msi.png']
        );

        Brand::updateOrCreate(
            ['slug' => 'nvidia'],
            ['name' => 'NVIDIA', 'logo' => 'logo_nvidia.png']
        );

        Brand::updateOrCreate(
            ['slug' => 'intel'],
            ['name' => 'Intel', 'logo' => 'logo_intel.png']
        );

        Brand::updateOrCreate(
            ['slug' => 'amd'],
            ['name' => 'AMD', 'logo' => 'logo_amd.png']
        );

        Brand::updateOrCreate(
            ['slug' => 'dell'],
            ['name' => 'Dell', 'logo' => 'logo_dell.png']
        );

        Brand::updateOrCreate(
            ['slug' => 'hp'],
            ['name' => 'HP', 'logo' => 'logo_hp.png']
        );

        Brand::updateOrCreate(
            ['slug' => 'lenovo'],
            ['name' => 'Lenovo', 'logo' => 'logo_lenovo.png']
        );

        Brand::updateOrCreate(
            ['slug' => 'samsung'],
            ['name' => 'Samsung', 'logo' => 'logo_samsung.png']
        );
    }
}
