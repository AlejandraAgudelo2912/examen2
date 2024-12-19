<?php

namespace App\Http\Resources;

use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Subcategory */
class SubcategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name'=>$this->name,
            'description'=>$this->description,
            'category' => $this->category->name,
            'products'=>$this->whenLoaded('products', function () {
                return $this->products->isNotEmpty() ? $this->products->map(function ($product) {
                    return $product->name;
                }) : null;
            }),
        ];
    }
}