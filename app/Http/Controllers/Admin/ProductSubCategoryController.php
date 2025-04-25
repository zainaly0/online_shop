<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;

class ProductSubCategoryController extends Controller
{
    public function index(Request $request){ 

        if(!empty($request->id)){
            $subcategory = SubCategory::where('category_id', $request->id)->orderBy('name', 'ASC')->get();
            return response()->json([
                'status' => true,
                'subcategory' => $subcategory,
            ]);
        }else{
            return response()->json([
                'status' => true,
                'subcategory' => [],
            ]);
        }

    }
}
