<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(20)->create();
        User::factory()->create([
            'username' => 'admin.user',
            'full_name' => 'Admin User',
            'email' => 'admin@jedlik.eu',
            'password' => bcrypt('adminpassword'),
        ]);
    }
}
