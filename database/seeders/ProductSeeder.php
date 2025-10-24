<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'لابتوب ديل',
                'description' => 'لابتوب قوي للمبرمجين',
                'price' => 2500.00,
                'image' => 'laptop1.jpg',
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'آيفون 14',
                'description' => 'هاتف ذكي حديث',
                'price' => 3500.00,
                'image' => 'iphone14.jpg',
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'سماعات سوني',
                'description' => 'سماعات لاسلكية عالية الجودة',
                'price' => 300.00,
                'image' => 'headphones.jpg',
                'category_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'كيبورد ميكانيكي',
                'description' => 'لوحة مفاتيح للألعاب',
                'price' => 200.00,
                'image' => 'keyboard.jpg',
                'category_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ماوس لاسلكي',
                'description' => 'ماوس دقيق وسريع',
                'price' => 150.00,
                'image' => 'mouse.jpg',
                'category_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'شاشة سامسونج',
                'description' => 'شاشة 24 بوصة عالية الدقة',
                'price' => 800.00,
                'image' => 'monitor.jpg',
                'category_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'كاميرا كانون',
                'description' => 'كاميرا احترافية للتصوير',
                'price' => 1800.00,
                'image' => 'camera.jpg',
                'category_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'تابلت سامسونج',
                'description' => 'جهاز لوحي متعدد الاستخدامات',
                'price' => 1200.00,
                'image' => 'tablet.jpg',
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'طابعة ليزر',
                'description' => 'طابعة سريعة وجودة عالية',
                'price' => 450.00,
                'image' => 'printer.jpg',
                'category_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'سماعات بلوتوث',
                'description' => 'سماعات صغيرة ومحمولة',
                'price' => 180.00,
                'image' => 'earbuds.jpg',
                'category_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('products')->insert($products);
    }

}
