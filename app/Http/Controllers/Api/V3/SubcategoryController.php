<?php

namespace App\Http\Controllers\Api\V3;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubcategoryResource;
use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubcategoryController extends Controller
{
    public function index()
    {

    }

    public function list()
    {
        $subcategory = Subcategory::with('category')->paginate(10);

        return SubcategoryResource::collection($subcategory);
    }
}
