<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Computers',
                'description' => 'Laptops, monitors, keyboards, and computer accessories.',
                'image_source' => 'public/frontend/assets/products/laptop.jpg',
            ],
            [
                'name' => 'Audio',
                'description' => 'Headphones, speakers, and everyday audio gear.',
                'image_source' => 'public/frontend/assets/products/headphones.jpg',
            ],
            [
                'name' => 'Mobiles',
                'description' => 'Smartphones, smartwatches, and mobile accessories.',
                'image_source' => 'public/frontend/assets/products/smartphone.jpg',
            ],
            [
                'name' => 'Accessories',
                'description' => 'Mice, keyboards, cameras, and useful tech add-ons.',
                'image_source' => 'public/frontend/assets/products/mouse.jpg',
            ],
        ];

        foreach ($categories as $category) {
            $imagePath = $this->copySeedImage($category['image_source'], 'categories');

            Category::updateOrCreate(
                ['name' => $category['name']],
                [
                    'description' => $category['description'],
                    'image' => $imagePath,
                    'status' => 'Active',
                ]
            );
        }
    }

    private function copySeedImage(string $sourcePath, string $folder): string
    {
        $source = base_path($sourcePath);
        $filename = basename($sourcePath);
        $relativePath = "{$folder}/{$filename}";
        $destination = storage_path("app/public/{$relativePath}");

        File::ensureDirectoryExists(dirname($destination));

        if (File::exists($source) && ! File::exists($destination)) {
            File::copy($source, $destination);
        }

        return $relativePath;
    }
}
