<?php

namespace App\Http\Controllers\Api\V3;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubcategoryRequest;
use App\Http\Resources\SubcategoryResource;
use App\Models\Subcategory;


/**
 * @OA\Info(
 *     description="API para gestionar las subcategorías, productos y categorías"
 * )
 */
class SubcategoryController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/v3/lists/subcategories",
     *     description="Devuelve un listado paginado de subcategorías",
     *     @OA\Response(
     *         response=200
     *     )
     * )
     */
    public function index()
    {
        $subcategories = Subcategory::all();
        return SubcategoryResource::collection($subcategories);
    }

    /**
     * @OA\Post(
     *     path="/api/v3/subcategories",
     *     description="Crea una nueva subcategoría",
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
    public function store(StoreSubcategoryRequest $request)
    {
        $subcategory = Subcategory::create($request->all());
        $subcategory->products()->sync($request->input('products',[]));
        return new SubcategoryResource($subcategory);
    }

    /**
     * @OA\Get(
     *     path="/api/v3/subcategories/{subcategory}",
     *     description="Devuelve los detalles de una subcategoría específica",
     *     @OA\Parameter(
     *         name="subcategory",
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
    public function show(Subcategory $subcategory)
    {
        return new SubcategoryResource($subcategory);
    }

    /**
     * @OA\Put(
     *     path="/api/v3/subcategories/{subcategory}",
     *     description="Actualiza los detalles de una subcategoría",
     *     @OA\Parameter(
     *         name="subcategory",
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
    public function update(Subcategory $subcategory, StoreSubcategoryRequest $request)
    {
        $subcategory->update($request->all());
        return new SubcategoryResource($subcategory);
    }

    /**
     * @OA\Delete(
     *     path="/api/v3/subcategories/{subcategory}",
     *     description="Elimina una subcategoría de la base de datos",
     *     @OA\Parameter(
     *         name="subcategory",
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
    public function destroy(Subcategory $subcategory){
        $subcategory->products()->detach();
        $subcategory->delete();
        return response()->noContent();
    }

    /**
     * @OA\Get(
     *     path="/api/v3/lists/subcategories",
     *     description="Devuelve un listado publico de subcategorías",
     *     @OA\Response(
     *         response=200,
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/SubcategoryResource")
     *         )
     *     )
     * )
     */
    public function list()
    {
        $subcategory = Subcategory::with('category')->paginate(10);

        return SubcategoryResource::collection($subcategory);
    }

    /**
     * @OA\Get(
     *     path="/api/v3/private/subcategories",
     *     description="Devuelve un listado privado de subcategorías con productos asociados para usuarios autenticados",
     *     @OA\Response(
     *         response=200,
     *         description="Listado de subcategorías privadas",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/SubcategoryResource")
     *         )
     *     )
     * )
     */
    public function privateList()
    {
        $subcategory = Subcategory::with('category','products')->paginate(12);

        return SubcategoryResource::collection($subcategory);
    }
}
