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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;


class ProductController extends Controller
{

    public function index(){
        
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
                    Log::info('tempImageInfo: '. $tempImageInfo);
                    $extArray = explode('.', $tempImageInfo->name); 

                    $ext = last($extArray); //return extension jpg,gif,png etc
                    Log::info('ext: '. $ext);

                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->image = "NULL";
                    $productImage->save();

                    $imageName = $product->id . '-' . $productImage->id . '-' . time() . '.' . $ext;
                    $productImage->image = $imageName;
                    $productImage->save();


                    // generate Product thumbnails

                    // Large Image
                    $sourcePath = public_path(). '/temp/'. $tempImageInfo->name;
                    $destPath = public_path().'/uploads/product/large/'.$tempImageInfo->name;
                    $image = Image::make($sourcePath);
                    $image->resize(1400, null, function($constraint){
                        $constraint->aspectRatio();
                    });
                    $image->save($destPath);

                    // Small Image
                    $sourcePath = public_path(). '/temp/'. $tempImageInfo->name;
                    $destPath = public_path().'/uploads/product/small/'.$tempImageInfo->name;
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
}
