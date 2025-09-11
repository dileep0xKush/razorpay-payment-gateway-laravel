<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name' => 'Wireless Mouse',
                'description' => 'Ergonomic wireless mouse with long battery life.',
                'price' => 799.00,
            ],
            [
                'name' => 'Bluetooth Headphones',
                'description' => 'Noise-cancelling over-ear headphones.',
                'price' => 2999.00,
            ],
            [
                'name' => 'Mechanical Keyboard',
                'description' => 'RGB backlit mechanical keyboard with blue switches.',
                'price' => 2499.00,
            ],
            [
                'name' => '4K Monitor',
                'description' => '27 inch UHD 4K monitor with HDR support.',
                'price' => 15999.00,
            ],
            [
                'name' => 'External SSD',
                'description' => 'Portable 1TB USB-C external solid-state drive.',
                'price' => 8499.00,
            ],
            [
                'name' => 'Smartwatch',
                'description' => 'Fitness tracker with heart rate monitor and GPS.',
                'price' => 4999.00,
            ],
            [
                'name' => 'Gaming Chair',
                'description' => 'Ergonomic gaming chair with adjustable height and lumbar support.',
                'price' => 10999.00,
            ],
            [
                'name' => 'Wireless Charger',
                'description' => 'Fast wireless charger compatible with all Qi-enabled devices.',
                'price' => 1499.00,
            ],
            [
                'name' => 'Action Camera',
                'description' => 'Waterproof 4K action camera with wide-angle lens.',
                'price' => 6999.00,
            ],
            [
                'name' => 'Laptop Stand',
                'description' => 'Adjustable aluminum laptop stand for desk setup.',
                'price' => 1299.00,
            ],
        ];

        DB::table('products')->insert($products);
    }
}
