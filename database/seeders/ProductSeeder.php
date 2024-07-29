<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::factory(10)->create()->each(function ($product) {
            ProductVariant::factory(3)->create(['product_id' => $product->id])->each(function ($variant) {
                ProductImage::factory(3)->create(['product_variant_id' => $variant->id]);
            });
        });
    }
}
