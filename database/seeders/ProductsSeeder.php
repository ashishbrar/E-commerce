<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ProductsSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'category' => 'Computers',
                'name' => 'AceBook Pro Laptop',
                'shortdescription' => 'Fast laptop for work, study, and daily productivity.',
                'description' => 'A reliable laptop with a sharp display, strong performance, and a clean portable design for everyday use.',
                'price' => 69999,
                'quantity' => 12,
                'image_source' => 'public/frontend/assets/products/laptop.jpg',
            ],
            [
                'category' => 'Computers',
                'name' => 'UltraWide Monitor',
                'shortdescription' => 'Crisp monitor for multitasking and entertainment.',
                'description' => 'A spacious monitor built for productivity, media, and comfortable viewing across long sessions.',
                'price' => 18999,
                'quantity' => 8,
                'image_source' => 'public/frontend/assets/products/monitor.jpg',
            ],
            [
                'category' => 'Accessories',
                'name' => 'Wireless Mouse',
                'shortdescription' => 'Compact wireless mouse with smooth tracking.',
                'description' => 'A comfortable mouse for daily browsing, office work, and travel setups.',
                'price' => 1299,
                'quantity' => 25,
                'image_source' => 'public/frontend/assets/products/mouse.jpg',
            ],
            [
                'category' => 'Accessories',
                'name' => 'Mechanical Keyboard',
                'shortdescription' => 'Responsive keyboard for typing and gaming.',
                'description' => 'A durable keyboard with tactile feedback and a clean layout for focused work.',
                'price' => 3499,
                'quantity' => 15,
                'image_source' => 'public/frontend/assets/products/keyboard.jpg',
            ],
            [
                'category' => 'Audio',
                'name' => 'Studio Headphones',
                'shortdescription' => 'Clear sound with comfortable all-day fit.',
                'description' => 'Headphones designed for music, calls, and immersive listening with reliable comfort.',
                'price' => 4999,
                'quantity' => 18,
                'image_source' => 'public/frontend/assets/products/headphones.jpg',
            ],
            [
                'category' => 'Audio',
                'name' => 'Bluetooth Speaker',
                'shortdescription' => 'Portable speaker with bold wireless sound.',
                'description' => 'A compact speaker that brings clear audio to rooms, desks, and small gatherings.',
                'price' => 2799,
                'quantity' => 20,
                'image_source' => 'public/frontend/assets/products/speaker.jpg',
            ],
            [
                'category' => 'Mobiles',
                'name' => 'Smartphone X',
                'shortdescription' => 'Modern smartphone with a bright display.',
                'description' => 'A sleek smartphone made for everyday communication, photos, and entertainment.',
                'price' => 32999,
                'quantity' => 10,
                'image_source' => 'public/frontend/assets/products/smartphone.jpg',
            ],
            [
                'category' => 'Mobiles',
                'name' => 'Smartwatch Active',
                'shortdescription' => 'Track fitness, notifications, and time in style.',
                'description' => 'A lightweight smartwatch with activity tracking and useful daily features.',
                'price' => 5999,
                'quantity' => 14,
                'image_source' => 'public/frontend/assets/products/smartwatch.jpg',
            ],
            [
                'category' => 'Accessories',
                'name' => 'Creator Camera',
                'shortdescription' => 'Compact camera for photos and content creation.',
                'description' => 'A handy camera for capturing sharp moments, product shots, and creator content.',
                'price' => 24999,
                'quantity' => 6,
                'image_source' => 'public/frontend/assets/products/camera.jpg',
            ],
        ];

        foreach ($products as $product) {
            $category = Category::where('name', $product['category'])->first();

            if (! $category) {
                continue;
            }

            $imagePath = $this->copySeedImage($product['image_source'], 'products');

            Product::updateOrCreate(
                ['name' => $product['name']],
                [
                    'category_id' => $category->id,
                    'shortdescription' => $product['shortdescription'],
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'quantity' => $product['quantity'],
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
