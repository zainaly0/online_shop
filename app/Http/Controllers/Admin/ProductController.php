<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function create(){
        $categories = Category::orderBy('name', 'ASC')->get(); 
        $brands = Brand::orderBy('name', 'ASC')->get();

        $data['brands']= $brands; 
        $data['categories']= $categories;

        return view('admin.products.create', $data);
    }


    public function store(Request $request){
        return $request->all();

    }
}
