<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{

    public function index(Request $request)
    {
        $subcategories = SubCategory::select('sub_categories.*', 'categories.name as categoryName')
            ->latest('id')
            ->leftJoin('categories', 'categories.id', 'sub_categories.category_id');
        if (!empty($request->get('keyword'))) {
            $subcategories = $subcategories->where('sub_categories.name', 'like', '%' . $request->get('keyword') . '%');
            $subcategories = $subcategories->orWhere('categories.name', 'like', '%' . $request->get('keyword') . '%');
        }
        $subcategories = $subcategories->paginate(10);
        return view('admin.sub_category.list', compact('subcategories'));
    }



    public function create()
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories;
        return view('admin.sub_category.create', $data);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:sub_categories,slug',
            'category' => 'required',
            'status' => 'required'
        ]);

        if ($validator->passes()) {

            $subcategory = new SubCategory();
            $subcategory->name = $request->name;
            $subcategory->slug = $request->slug;
            $subcategory->status = $request->status;
            $subcategory->category_id = $request->category;
            $subcategory->save();

            $request->session()->flash('success', 'Sub Category created successfully');

            return response()->json([
                'status' => true,
                'message' => 'Sub Category created successfully'
            ]);

        } else {
            return response()->json([
                'status' => 'false',
                'errors' => $validator->errors()
            ]);
        }

    }



    public function edit($subcategory, Request $request)
    {
        $subCategory = SubCategory::find($subcategory);
        if (empty($subCategory)) {
            $request->session()->flash('error', 'Record not found');
            return redirect()->route('sub-categories.index');
        }

        $category = Category::orderBy('name', 'ASC')->get();
        $data['subCategory'] = $subCategory;
        $data['categories'] = $category;

        return view('admin.sub_category.edit', $data);

    }


    public function update($id, Request $request)
    {

        $subcategory = SubCategory::find($id);

        if (empty($subcategory)) {
            $request->session()->flash('error', 'Sub Category not Found');

           return response([
            'status' => false,
            'notFound' => true,
            'message' => 'SubCategory not Found'
           ]);
        }

        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:sub_categories,slug,'.$subcategory->id.',id',
            'category' => 'required',
            'status' => 'required'
        ]);

        if ($validator->passes()) {
            $subcategory->name = $request->name;
            $subcategory->slug = $request->slug;
            $subcategory->status = $request->status;
            $subcategory->category_id = $request->category;
            $subcategory->update();

            $request->session()->flash('success', 'Sub Category created successfully');

            return response()->json([
                'status' => true,
                'message' => 'Sub Category created successfully'
            ]);

        } else {
            return response()->json([
                'status' => 'false',
                'errors' => $validator->errors()
            ]);
        }

    }




    public function destroy($subCategory, Request $request){

        $subCategory = SubCategory::find($subCategory);

        if(empty($subCategory)){
            $request->session()->flash('error', 'Record not found');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Subcategory not found',
            ]);
        }


        $subCategory->delete();
        $request->session()->flash('success', 'SubCategory delete successfully');

        return response()->json([
            'status' => true,
            'message' => 'SubCategory delete successfully'
        ]);

    }
}
