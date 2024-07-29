<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;

class OrdersTableSeeder extends Seeder
{
    public function run()
    {
        // Create 20 fake orders
        Order::factory()->count(20)->create();
    }
}
