<?php

namespace App\Http\Controllers\Api\V3;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;

/**
 * @OA\Info(
 *     description="API para gestionar las subcategorías, productos y categorías"
 * )
 */
class ProductController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/v3/products",
     *     description="Devuelve un listado paginado de productos",
     *     @OA\Response(
     *         response=200
     *     )
     * )
     */
    public function index()
    {
        $products = Product::with('subcategories')->paginate(9);

        return ProductResource::collection($products);
    }

    /**
     * @OA\Get(
     *     path="/api/v3/products/{product}",
     *     description="Devuelve los detalles de un producto específica",
     *     @OA\Parameter(
     *         name="product",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *     ),
     *     @OA\Response(
     *         response=404,
     *     )
     * )
     */
    public function show(Product $product)
    {

        return new ProductResource($product);
    }

    /**
     * @OA\Put(
     *     path="/api/v3/products/{product}",
     *     description="Actualiza los detalles de un producto",
     *     @OA\Parameter(
     *         name="product",
     *         required=true,
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *     ),
     *     @OA\Response(
     *         response=400,
     *     )
     * )
     */
    public function update(Product $product, StoreProductRequest $request)
    {
        $product->update($request->all());
        return new ProductResource($product);
    }

    /**
     * @OA\Delete(
     *     path="/api/v3/products/{product}",
     *     description="Elimina un producto de la base de datos",
     *     @OA\Parameter(
     *         name="product",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=204,
     *     ),
     *     @OA\Response(
     *         response=404,
     *     )
     * )
     */
    public function destroy(Product $product)
    {
        $product->subcategories()->detach();
        $product->delete();
        return response()->noContent();
    }

    /**
     * @OA\Post(
     *     path="/api/v3/products",
     *     description="Crea un nuevo producto",
     *     @OA\RequestBody(
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=201,
     *     ),
     *     @OA\Response(
     *         response=400,
     *     )
     * )
     */
    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->all());
        $product->subcategories()->sync($request->input('subcategories', []));
        return new ProductResource($product);
    }
}
