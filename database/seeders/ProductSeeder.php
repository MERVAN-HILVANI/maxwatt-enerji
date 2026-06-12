<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['title' => 'Jinko 550W Monokristal Panel', 'category_id' => 1, 'user_id' => 1, 'price' => 2850.00, 'stock' => 50, 'description' => '550W yüksek verimli monokristal güneş paneli'],
            ['title' => 'Longi 450W Half-Cell Panel', 'category_id' => 1, 'user_id' => 1, 'price' => 2200.00, 'stock' => 30, 'description' => '450W yarım hücreli panel'],
            ['title' => 'Huawei SUN2000 5KTL İnverter', 'category_id' => 2, 'user_id' => 1, 'price' => 18500.00, 'stock' => 15, 'description' => '5kW akıllı on-grid inverter'],
            ['title' => 'Growatt 3KW Off-Grid İnverter', 'category_id' => 2, 'user_id' => 1, 'price' => 12000.00, 'stock' => 10, 'description' => '3kW off-grid inverter'],
            ['title' => 'Pylontech 100Ah LiFePO4', 'category_id' => 3, 'user_id' => 1, 'price' => 22000.00, 'stock' => 20, 'description' => '48V 100Ah lityum demir fosfat batarya'],
            ['title' => 'Trimetals Çatı Montaj Seti', 'category_id' => 4, 'user_id' => 1, 'price' => 1500.00, 'stock' => 100, 'description' => '6 panel için çatı montaj sistemi'],
            ['title' => '6mm² Solar Kablo (100m)', 'category_id' => 5, 'user_id' => 1, 'price' => 850.00, 'stock' => 200, 'description' => 'UV dayanımlı 6mm² solar kablo'],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
