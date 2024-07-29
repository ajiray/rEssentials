<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $products = [
            ['name' => 'Air Jordan 1', 'brand' => 'Nike'],
            ['name' => 'Ultraboost 21', 'brand' => 'Adidas'],
            ['name' => 'Classic Leather', 'brand' => 'Reebok'],
            ['name' => 'Chuck Taylor All Star', 'brand' => 'Converse'],
            ['name' => 'Gel-Lyte III', 'brand' => 'ASICS'],
            ['name' => 'Puma Suede', 'brand' => 'Puma'],
            ['name' => 'Sk8-Hi', 'brand' => 'Vans'],
            ['name' => 'Club C 85', 'brand' => 'Reebok'],
            ['name' => 'Stan Smith', 'brand' => 'Adidas'],
            ['name' => 'Air Force 1', 'brand' => 'Nike']
        ];

        $product = $this->faker->randomElement($products);

        return [
            'name' => $product['name'],
            'brand' => $product['brand'],
            'description' => $this->faker->sentence,
            'category' => $this->faker->randomElement(['Perfume', 'Cologne', 'Other']),
        ];
    }
}
