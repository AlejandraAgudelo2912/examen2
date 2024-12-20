<?php

namespace App\Http\Controllers\Api\V3;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * @OA\Get (
     *     path="/categories",
     *     tags={"Categories"},
     *     summary="Get List all categories",
     *     @OA\Response(
     *          response="200",
     *          description="Succesful operation",
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response="403",
     *         description="Forbidden",
     *     )
     * )
     */
    public function index()
    {
        abort_if(! auth()->user()->tokenCan('categories-list'), 403);

        return CategoryResource::collection(Cache::rememberForever('categories', function () {
            return Category::all();
        }));
    }

    public function show(Category $category)
    {
        //abort_if(! auth()->user()->tokenCan('categories-show'), 403);

        return new CategoryResource($category);
    }

    /**
     * Store a new category
     *
     * Creating a new category
     *
     * @bodyParam name string required The name of the category. Example: Electronics
     */
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->all();

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $name = Str::uuid() . '.' . $file->extension();
            $file->storeAs('categories', $name, 'public');
            $data['photo'] = $name;
        }

        $category = Category::create($data);

        return new CategoryResource($category);
    }

    public function update(Category $category, StoreCategoryRequest $request)
    {
        $category->update($request->all());

        return new CategoryResource($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        //return response(null, Response::HTTP_NO_CONTENT);
        return response()->noContent();
    }

    public function list()
    {
        return CategoryResource::collection(Category::all());
    }
}
