<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SubCategory;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;


class ProductController extends Controller
{

    public function index(Request $request)
    {
        $products = Product::latest('id')->with('product_images');
        if ($request->keyword) {
            $products->where('title', 'like', '%' . $request->keyword . '%');
        }

        $products = $products->paginate();

        // dd($products);
        $data['products'] = $products;
        return view('admin.products.list', $data);
    }
    public function create()
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();

        $data['brands'] = $brands;
        $data['categories'] = $categories;

        return view('admin.products.create', $data);
    }


    public function store(Request $request)
    {

        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required',
            'sku' => 'required|unique:products',
            'track_qty' => 'required|in:Yes,No',
            'is_featured' => 'required|in:Yes,No',
            'category' => 'required|exists:categories,id',
            'sub_category' => 'nullable|exists:sub_categories,id',
            'brand' => 'nullable|exists:brands,id',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        if ($validator->passes()) {
            $product = new Product();
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->save();

            if (!empty($request->image_array)) {
                foreach ($request->image_array as $temp_image_id) {
                    $tempImageInfo = TempImage::find($temp_image_id);
                    Log::info('tempImageInfo: ' . $tempImageInfo);
                    $extArray = explode('.', $tempImageInfo->name);

                    $ext = last($extArray); //return extension jpg,gif,png etc
                    Log::info('ext: ' . $ext);

                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->image = "NULL";
                    $productImage->save();

                    $imageName = $product->id . '-' . $productImage->id . '-' . time() . '.' . $ext;
                    $productImage->image = $imageName;
                    $productImage->save();


                    // generate Product thumbnails

                    // Large Image
                    $sourcePath = public_path() . '/temp/' . $tempImageInfo->name;
                    $destPath = public_path() . '/uploads/product/large/' . $imageName;
                    $image = Image::make($sourcePath);
                    $image->resize(1400, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $image->save($destPath);

                    // Small Image 
                    $destPath = public_path() . '/uploads/product/small/' . $imageName;
                    $image = Image::make($sourcePath);
                    $image->fit(300, 300);
                    $image->save($destPath);
                }
            }

            $request->session()->flash('success', 'Product create successfully');

            return response()->json([
                'status' => true,
                'message' => 'Product create successfully',
            ]);
        }
    }


    public function edit($id, Request $request)
    {
        $product = Product::find($id);
        if (empty($product)) {
            $request->session()->flash('error', 'Record not found');
            return redirect()->route('product.index');
        }

        //Fetch Product Images 
        $productImages = ProductImage::where('product_id', $product->id)->get();
        $subCategories = SubCategory::where('category_id', $product->category_id)->get();
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();

        $data['brands'] = $brands;
        $data['categories'] = $categories;
        $data['product'] = $product;
        $data['subCategories'] = $subCategories;
        $data['productImages'] = $productImages;


        return view('admin.products.edit', $data);
    }


    public function update($id, Request $request)
    {

        $product = Product::find($id);
        if (empty($product)) {
            $request->session()->flash('error', 'Record not found');
            return redirect()->route('product.index');
        }

        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products,slug,' . $product->id . ',id',
            'price' => 'required',
            'sku' => 'required|unique:products,sku,'. $product->id.',id',
            'track_qty' => 'required|in:Yes,No',
            'is_featured' => 'required|in:Yes,No',
            'category' => 'required|exists:categories,id',
            'sub_category' => 'nullable|exists:sub_categories,id',
            'brand' => 'nullable|exists:brands,id',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        if ($validator->passes()) {
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->update();

            // if (!empty($request->image_array)) {
            //     foreach ($request->image_array as $temp_image_id) {
            //         $tempImageInfo = TempImage::find($temp_image_id);
            //         Log::info('tempImageInfo: '. $tempImageInfo);
            //         $extArray = explode('.', $tempImageInfo->name); 

            //         $ext = last($extArray); //return extension jpg,gif,png etc
            //         Log::info('ext: '. $ext);

            //         $productImage = new ProductImage();
            //         $productImage->product_id = $product->id;
            //         $productImage->image = "NULL";
            //         $productImage->save();

            //         $imageName = $product->id . '-' . $productImage->id . '-' . time() . '.' . $ext;
            //         $productImage->image = $imageName;
            //         $productImage->save();


            //         // generate Product thumbnails

            //         // Large Image
            //         $sourcePath = public_path(). '/temp/'. $tempImageInfo->name;
            //         $destPath = public_path().'/uploads/product/large/'.$imageName;
            //         $image = Image::make($sourcePath);
            //         $image->resize(1400, null, function($constraint){
            //             $constraint->aspectRatio();
            //         });
            //         $image->save($destPath);

            //         // Small Image 
            //         $destPath = public_path().'/uploads/product/small/'.$imageName;
            //         $image = Image::make($sourcePath);
            //         $image->fit(300, 300);
            //         $image->save($destPath);
            //     }

            // }

            $request->session()->flash('success', 'Product updated successfully');

            return response()->json([
                'status' => true,
                'message' => 'Product updated successfully',
            ]);
        }
    }

    public function destroy($id, Request $request){
        $product = Product::find($id);

        if(empty($product)){
            $request->session()->flash('error', 'Product Not found');
            return response()->json([
                'status' =>  false,
                'notFound' => true,
                'message' => 'data not found'
            ]);
        }


        $productImages = ProductImage::where('product_id', $product->id)->get();

        if(!empty($productImages)){
            foreach($productImages as $productImage){
                File::delete(public_path('uploads/product/large/'. $productImage->image));
                File::delete(public_path('uploads/product/small/'. $productImage->image));

            }

            ProductImage::where('product_id', $id)->delete();

        }

        $product->delete();
        $request->session()->flash('success', 'Product deleted Successfully');

        return response()->json([
            'status' => true,
            'message' => 'Product deleted Successfully',
        ]);





    }



}
