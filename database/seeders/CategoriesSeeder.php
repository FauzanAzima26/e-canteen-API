<?php

namespace Database\Seeders;

use App\Models\Categories;
use Illuminate\Support\Str;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Categories::insert([
            [
                'uuid' => Str::uuid(),
                'name' => 'Food',
                'slug' => 'food',
                'created_at' => now(),
                'updated_at' => now() 
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Drink',
                'slug' => 'drink',
                'created_at' => now(),
                'updated_at' => now() 
            ]
        ]);
    }
}
