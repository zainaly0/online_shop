<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('admin.category.list', compact('categories'));

    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
        // return redirect()->back()->withErrors($validator)->withInput();

        if ($validator->passes()) {
            Category::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'status' => $request->status,
            ]);

            $request->session()->flash('success', 'Category added successfully');

            return response()->json(['status' => true, 'message' => 'Category added successfully']);
        }

    }

    public function edit()
    {

    }

    public function update()
    {

    }

    public function destroy()
    {

    }



    public function categorySearch(Request $request){
        $category = Category::where('name','like', "%{$request->name}%")->get();
        
        return response()->json([
            'status' => true,
            'data' => $category,
        ]);
    }
}
