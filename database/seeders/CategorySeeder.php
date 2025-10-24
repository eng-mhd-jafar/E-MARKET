<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Living Room Sofas',
            'Dining Tables',
            'Bedroom Sets',
            'Office Chairs',
            'Coffee Tables',
            'Bookshelves',
            'TV Stands',
            'Wardrobes',
            'Dressers',
            'Accent Chairs'
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert(['name' => $category]);
        }
    }
}
