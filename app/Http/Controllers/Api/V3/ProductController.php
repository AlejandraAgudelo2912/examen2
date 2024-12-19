<?php

namespace App\Http\Controllers\Api\V3;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('subcategories')->paginate(9);

        return ProductResource::collection($products);
    }

    public function show(Product $product)
    {

        return new ProductResource($product);
    }

    public function update(Product $product, StoreProductRequest $request)
    {
        $product->update($request->all());
        return new ProductResource($product);
    }

    public function destroy(Product $product)
    {
        $product->subcategories()->detach();
        $product->delete();
        return response()->noContent();
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->all());
        $product->subcategories()->sync($request->input('subcategories', []));
        return new ProductResource($product);
    }
}
