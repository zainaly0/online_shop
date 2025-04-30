<?php

use App\Http\Controllers\Admin\SubCategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\ProductSubCategoryController;
use App\Http\Controllers\Admin\TempImagesController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});




Route::group(['prefix' => 'admin'], function () {
    Route::group(['middleware' => 'admin.guest'], function () {
        Route::get('/login', [AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');

    });

    Route::group(['middleware' => 'admin.auth'], function () {
        Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout', [HomeController::class, 'logout'])->name('admin.logout');

        // category routes
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories/store', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');


        //sub category routes
        Route::get('/sub-categories/create', [SubCategoryController::class, 'create'])->name('sub-categories.create');
        Route::post('/sub-categories/store', [SubCategoryController::class, 'store'])->name('sub-categories.store');
        Route::get('/sub-categories', [SubCategoryController::class, 'index'])->name('sub-categories.index');
        Route::get('/sub-categories/{subcategory}/edit', [SubCategoryController::class, 'edit'])->name('sub-categories.edit');
        Route::put('/sub-categories/{subCategory}', [SubCategoryController::class, 'update'])->name('sub-categories.update');
        Route::delete('/sub-categories/{subCategory}', [SubCategoryController::class, 'destroy'])->name('sub-categories.destroy');

        // brand routes
        Route::get('brand/create', [BrandController::class, 'create'])->name('brand.create');
        Route::post('/brand/store', [BrandController::class, 'store'])->name('brand.store');
        Route::get('/brand', [BrandController::class, 'index'])->name('brand.index');
        Route::get('/brand/{brand}/edit', [BrandController::class, 'edit'])->name('brand.edit');
        Route::put('/brand/{brand}', [BrandController::class, 'update'])->name('brand.update');
        Route::delete('/brand/{brand}', [BrandController::class, 'destroy'])->name('brand.destroy');

        
        // products routes
        Route::get('product/create', [ProductController::class, 'create'])->name('product.create');
        Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');
        Route::get('/product', [ProductController::class, 'index'])->name('product.index');
        Route::get('/product/{product}/edit', [ProductController::class, 'edit'])->name('product.edit');
        Route::put('/product/{product}', [ProductController::class, 'update'])->name('product.update');
        // Route::delete('/product/{product}', [ProductController::class, 'destroy'])->name('product.destroy');

        Route::get('/product/sub-categories', [ProductSubCategoryController::class, 'index'])->name('product-subcategories.find');

        Route::post('/product-images/update', [ProductImageController::class, 'update'])->name('product-images.update');
        Route::delete('/product-images/delete', [ProductImageController::class, 'destroy'])->name('product-images.destroy'); 
        Route::get('/getSlug', function(Request $request){
            $slug = '';
            if(!empty($request->title)){
                $slug  = Str::slug($request->title);
            }


            return response()->json([
                'status' => true,
                'slug' => $slug
            ]);
        })->name('getSlug');

        Route::get('/getcategory',[CategoryController::class, 'categorySearch'])->name('category.search');
        Route::post('/upload-temp-image', [TempImagesController::class, 'create'])->name('temp-images.create');


    });
});