<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Seed kategori dokumen.
     */
    public function run(): void
    {
        Category::updateOrCreate(
            ['name' => 'As Built'],
            ['name' => 'As Built']
        );
    }
}