<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CategoryIconSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        /**
         * Run the database seeds.
         */

        $this->clearExistingIcons();

        // Create storage directory if it doesn't exist
        $storagePath = storage_path('app/public/category-icons');
        if (!File::exists($storagePath)) {
            File::makeDirectory($storagePath, 0755, true);
        }

        // Define category icon mappings
        $categoryIcons = [
            'Living Room Sofas' => 'sofa-icon.png',
            'Dining Tables' => 'dining-table-icon.png',
            'Bedroom Sets' => 'bedroom-icon.png',
            'Office Chairs' => 'office-chair-icon.png',
            'Coffee Tables' => 'coffee-table-icon.png',
            'Bookshelves' => 'bookshelf-icon.png',
            'TV Stands' => 'tv-stand-icon.png',
            'Wardrobes' => 'wardrobe-icon.png',
            'Dressers' => 'dresser-icon.png',
            'Accent Chairs' => 'accent-chair-icon.png'
        ];

        // Get all categories
        $categories = Category::all();

        foreach ($categories as $category) {
            $iconFileName = $categoryIcons[$category->name] ?? null;

            if ($iconFileName) {
                $sourcePath = public_path('category-icons/' . $iconFileName);

                if (File::exists($sourcePath)) {
                    // Copy to storage
                    $destinationPath = 'category-icons/' . $iconFileName;

                    if (!Storage::disk('public')->exists($destinationPath)) {
                        Storage::disk('public')->put($destinationPath, File::get($sourcePath));
                    }

                    // Update category with icon
                    $category->update(['icon' => $iconFileName]);
                } else {
                    $this->command->warn("Icon file not found: {$iconFileName} for category: {$category->name}");
                }
            }
        }

        $this->command->info('Category icons seeded successfully!');
    }

    private function clearExistingIcons(): void
    {
        $storagePath = storage_path('app/public/category-icons');
        if (File::exists($storagePath)) {
            File::deleteDirectory($storagePath);
        }
    }

}
