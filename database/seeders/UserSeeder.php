<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@computerstore.com'],
            [
                'name' => 'ComputerStore Admin',
                'password' => Hash::make('admin123'),
                'phone' => '0987654321',
                'address' => 'Ha Noi, Viet Nam',
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'khachhang@gmail.com'],
            [
                'name' => 'Nguyen Van Khach',
                'password' => Hash::make('client123'),
                'phone' => '0123456789',
                'address' => 'TP. Ho Chi Minh, Viet Nam',
                'role' => 'customer',
            ]
        );
    }
}
