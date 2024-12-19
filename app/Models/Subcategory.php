<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;

    protected $fillable = ['category_id','name', 'description'];

    /**
     * @OA\Property(
     *     property="category",
     * )
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @OA\Property(
     *     property="products",
     *     type="array",
     */
    public function products()
    {
        return $this->belongsToMany(Product::class,'product_subcategory');
    }
}
