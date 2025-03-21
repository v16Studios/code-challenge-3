<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Product::factory()->create([
            'name' => 'Red Widget',
            'code' => 'R01',
            'price' => 32.95,
        ]);

        Product::factory()->create([
            'name' => 'Green Widget',
            'code' => 'G01',
            'price' => 24.95,
        ]);

        Product::factory()->create([
            'name' => 'Blue Widget',
            'code' => 'B01',
            'price' => 7.95,
        ]);
    }
}
