<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Factories\OrderItemFactory;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrderItemFactory::new()->count(100)->create();
    }
}
