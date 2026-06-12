<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Güneş Panelleri', 'slug' => 'gunes-panelleri', 'description' => 'Monokristal ve polikristal güneş panelleri'],
            ['name' => 'İnverterler', 'slug' => 'inverterler', 'description' => 'On-grid ve off-grid inverterler'],
            ['name' => 'Bataryalar', 'slug' => 'bataryalar', 'description' => 'Lityum ve AGM bataryalar'],
            ['name' => 'Montaj Sistemleri', 'slug' => 'montaj-sistemleri', 'description' => 'Çatı ve saha montaj ekipmanları'],
            ['name' => 'Kablolar & Aksesuarlar', 'slug' => 'kablolar-aksesuarlar', 'description' => 'Solar kablolar ve bağlantı ekipmanları'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}
