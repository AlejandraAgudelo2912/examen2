<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
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
        $categories=Category::factory(15)->create();
        $categories->each(function (Category $category) {
            $subcategories=Subcategory::factory(5)->create(['category_id'=>$category->id]);
            $subcategories->each(function (Subcategory $subcategory) {
                $products=Product::factory(5)->create();
                foreach ($products as $product) {
                    $subcategory->products()->attach($product->id);
                }
            });
        });
    }
}
