<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('cart_items')->insert([

            [
                'user_id' => 1,
                'product_id' => 1,
                'quantity' => 2,
            ],

            [
                'user_id' => 1,
                'product_id' => 2,
                'quantity' => 1,
            ],

            [
                'user_id' => 1,
                'product_id' => 3,
                'quantity' => 4,
            ]

        ]);
    }
}