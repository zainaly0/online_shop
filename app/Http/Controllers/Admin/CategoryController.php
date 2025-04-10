<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index(Request $request)
    {

        $categories = Category::latest();

        if (!empty($request->get('keyword'))) {
            $categories = $categories->where('name', 'like', '%' . $request->get('keyword') . '%');
        }

        $categories = $categories->paginate(10);
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
            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status; 
            $category->save();

            if(!empty($request->image_id)){
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);

                $newImageName = (string) $category->id. '.' .$ext;
                $sPath = public_path() . '/temp/' . $tempImage->name;
                $dPath = public_path() . '/uploads/category/' . $newImageName;
                Log::info('public destination path ' . $dPath );
                if(File::exists($sPath)){
                    File::copy($sPath, $dPath);
                }
                $category->image = $newImageName;
                $category->save();
            }
            


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



    // public function categorySearch(Request $request){
    //     $category = Category::where('name','like', "%{$request->name}%")->get();

    //     return response()->json([
    //         'status' => true,
    //         'data' => $category,
    //     ]);
    // }
}
