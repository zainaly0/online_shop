<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{


    public function index(Request $request){
        $brands= Brand::latest('id');
        if($request->get('keyword')){
            $brands = $brands->where('name', 'like', '%'. $request->keyword. '%');
        }
        
        $brands = $brands->paginate(10);
        return view('admin.brands.list', compact('brands'));
    }


    public function create(){
        return view('admin.brands.create');
    }



    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands',
            'status' => 'required'
        ]); 


        if($validator->fails()){
            $request->session()->flash('error', 'validation error');
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

        if($validator->passes()){
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            $request->session()->flash('success' , ' Brand Created Successfully');
            return response()->json([
                'status' => true,
                'message' => 'data insert successfully'
            ]);
        }
    }


    public function edit($id, Request $reqeust){
        $brand = Brand::find($id);
        if(empty($brand)){
            return redirect()->route('brand.index');
        }

        return view('admin.brands.edit', compact('brand'));
    }

    public function update($id, Request $request){

        $brand = Brand::find($id);
        if(empty($brand)){
            $request->session()->flash('Recode not found');
            return redirect()->route('brand.index');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,'. $brand->id. ',id',
            'status' => 'required'
        ]); 


        if($validator->fails()){
            $request->session()->flash('error', 'validation error');
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

        if($validator->passes()){ 
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->update();

            $request->session()->flash('success' , ' Brand udpate Successfully');
            return response()->json([
                'status' => true,
                'message' => 'Brand udpate successfully'
            ]);
        }
    }

    public function destroy($id, Request $request){
        $brand = Brand::find($id);
        if(empty($brand)){
            $request->session()->flash('error', 'Record not found for delete');
            return redirect()->route('brand.index');
        }

        $brand->delete();
        $request->session()->flash('success', 'record delete successfully');
        // return redirect()->route('brand.list');

        return response()->json([
            'status' => true,
            'message' => 'Brand deleted successfully',
        ]);
    }
}
