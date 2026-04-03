<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Factories\CategoryFactory;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Ételek',
        ]);

        Category::create([
            'name' => 'Italok',
        ]);

        Category::create([
            'name' => 'Snackek',
        ]);

        Category::create([
            'name' => 'Péksütemények',
        ]);

        Category::create([
            'name' => 'Édességek',
        ]);
    }
}
