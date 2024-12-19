<?php

namespace App\Http\Controllers\Api\V3;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubcategoryRequest;
use App\Http\Resources\SubcategoryResource;
use App\Models\Subcategory;

class SubcategoryController extends Controller
{
    public function index()
    {
        $subcategories = Subcategory::all();
        return SubcategoryResource::collection($subcategories);
    }

    public function store(StoreSubcategoryRequest $request)
    {
        $subcategory = Subcategory::create($request->all());
        $subcategory->products()->sync($request->input('products',[]));
        return new SubcategoryResource($subcategory);
    }

    public function show(Subcategory $subcategory)
    {
        return new SubcategoryResource($subcategory);
    }

    public function update(Subcategory $subcategory, StoreSubcategoryRequest $request)
    {
        $subcategory->update($request->all());
        return new SubcategoryResource($subcategory);
    }

    public function destroy(Subcategory $subcategory){
        $subcategory->products()->detach();
        $subcategory->delete();
        return response()->noContent();
    }

    public function list()
    {
        $subcategory = Subcategory::with('category')->paginate(10);

        return SubcategoryResource::collection($subcategory);
    }

    public function privateList()
    {
        $subcategory = Subcategory::with('category','products')->paginate(12);

        return SubcategoryResource::collection($subcategory);
    }
}
