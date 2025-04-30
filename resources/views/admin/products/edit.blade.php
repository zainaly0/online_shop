@extends('admin.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Product</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="products.html" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <form action="" name="productForm" id="productForm" method="post">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="title">Title</label>
                                        <input type="text" name="title" id="title" class="form-control" placeholder="Title" value="{{$product->title}}">
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="slug">Slug</label>
                                        <input type="text" name="slug" id="slug" class="form-control" placeholder="slug" readonly value="{{$product->slug}}">
                                        <p class="error"></p>

                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" cols="30" rows="10" class="summernote" placeholder="Description">{!! $product->description !!}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Media</h2>
                            <div id="image" class="dropzone dz-clickable">
                                <div class="dz-message needsclick">
                                    <br>Drop files here or click to upload.<br><br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="product-gallery">
                        @if($productImages->isNotEmpty())
                            @foreach ($productImages as $productImage)
                                <div class="col-md-3" id="image-row-{{$productImage->id}}">
                                    <div class="card">
                                        <input type="hidden" name="image_array[]" value="{{$productImage->id}}">
                                        <img src="{{ asset('/uploads/product/small/'.$productImage->image) }}" class="card-img-top" alt="...">
                                        <div class="card-body">
                                            <a href="javascript:void(0)" onclick="deleteImage({{$productImage->id}})" class="btn btn-danger">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Pricing</h2>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="price">Price</label>
                                        <input type="text" name="price" id="price" class="form-control" placeholder="Price" value="{{$product->price}}">
                                        <p class="error"></p>

                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="compare_price">Compare at Price</label>
                                        <input type="text" name="compare_price" id="compare_price" class="form-control" placeholder="Compare Price" value="{{$product->compare_price}}">
                                        <p class="text-muted mt-3">
                                            To show a reduced price, move the product’s original price into Compare at price. Enter a lower value into Price.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Inventory</h2>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sku">SKU (Stock Keeping Unit)</label>
                                        <input type="text" name="sku" id="sku" class="form-control" placeholder="sku" value="{{$product->sku}}">
                                        <p class="error"></p>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="barcode">Barcode</label>
                                        <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Barcode" value="{{$product->barcode}}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <div class="custom-control custom-checkbox">
                                            <input type="hidden" name="track_qty" value="No">
                                            <input class="custom-control-input" type="checkbox" id="track_qty" name="track_qty" value="Yes" {{($product->track_qty == 'Yes') ? 'Checked' : ''}}>
                                            <label for="track_qty" class="custom-control-label">Track Quantity</label>
                                            <p class="error"></p>

                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <input type="number" min="0" name="qty" id="qty" class="form-control" placeholder="Qty" value="{{$product->qty}}">
                                        <p class="error"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Product status</h2>
                            <div class="mb-3">
                                <select name="status" id="status" class="form-control">
                                    <option value="1" {{$product->status == 1 ? 'selected' : ''}}>Active</option>
                                    <option value="0" {{$product->status == 0 ? 'selected' : ''}}>Block</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h2 class="h4  mb-3">Product category</h2>
                            <div class="mb-3">
                                <label for="category">Category</label>
                                <select name="category" id="category" class="form-control">
                                    <option value="">Select a Category</option>
                                    @if($categories->isNotEmpty())
                                    @foreach ($categories as $category)
                                    <option value="{{$category->id}}" {{($product->category_id == $category->id) ? 'selected' : ''}}>{{$category->name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <p class="error"></p>

                            </div>
                            <div class="mb-3">
                                <label for="sub_category">Sub Category</label>
                                <select name="sub_category" id="sub_category" class="form-control">
                                    <option value="">Select a Sub Category</option>
                                    @if($subCategories->isNotEmpty())
                                    @foreach ($subCategories as $subcategory)
                                    <option value="{{ $subcategory->id }}" {{ $product->sub_category_id == $subcategory->id ? 'selected' : '' }}>
                                        {{ $subcategory->name }}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Product brand</h2>
                            <div class="mb-3">
                                <select name="brand" id="brand" class="form-control">
                                    <option value="">Select a Brand</option>
                                    @if($brands->isNotEmpty())
                                    @foreach ($brands as $brand)
                                    <option value="{{$brand->id}}" {{($product->brand_id == $brand->id) ? 'selected' : ''}}>{{$brand->name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Featured product</h2>
                            <div class="mb-3">
                                <select name="is_featured" id="is_featured" class="form-control">
                                    <option value="No" {{($product->is_featured == "No") ? 'selected' : ''}}>No</option>
                                    <option value="Yes" {{($product->is_featured == "Yes") ? 'selected' : ''}}>Yes</option>
                                </select>
                                <p class="error"></p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{route('product.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </div>



    </form>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection

@section('customJs')

<script>
    $("#title").on('keyup', function() {
        var element = $(this);
        $("button[type=submit]").prop('disabled', true);

        $.ajax({
            url: "{{route('getSlug')}}"
            , method: 'get'
            , data: {
                'title': element.val()
            , }
            , dataType: 'json'
            , success: function(response) {
                if (response['status'] == true) {
                    $('#slug').val(response.slug)
                    $('button[type=submit]').prop('disabled', false);
                }

            }
            , error: function() {
                console.log('error')
            }
        })
    })



    $('#productForm').submit(function(event) {
        event.preventDefault();
        var formArray = $(this).serializeArray();
        $("button[type='submit']").prop('disabled', true)

        $.ajax({
            url: "{{route('product.update', $product->id)}}"
            , method: 'put'
            , data: formArray
            , dataType: 'json'
            , success: function(response) {
                $("button[type='submit']").prop('disabled', false)


                if (response['status'] == true) {

                    $('.class').removeClass('invalid-feedback').html('')
                    $("input[type='text'], select, input[type='number']").removeClass('is-invalid')
                    window.location.href = "{{route('product.index')}}"

                } else {
                    var errors = response['errors']
                    console.log(errors['title'])
                    // if (errors['title']) {
                    //     $('#title').addClass('is-invalid')
                    //         .siblings('p')
                    //         .addClass('invalid-feedback')
                    //         .html(errors['title']);
                    // } else {
                    //     $('#title').removeClass('is-invalid')
                    //         .siblings('p')
                    //         .removeClass('invalid-feedback')
                    //         .html()
                    // }

                    $('.class').removeClass('invalid-feedback').html('')
                    $("input[type='text'], select, input[type='number']").removeClass('is-invalid')
                    $.each(errors, function(key, value) {
                        $(`#${key}`).addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(`${value}`)
                    });

                }
            }
            , error: function() {
                console.log('error')
            }
        });

    })

    $('#category').on('change', function() {
        var categoryID = $(this).val();
        if (categoryID) {
            $.ajax({
                url: "{{route('product-subcategories.find')}}"
                , method: 'get'
                , data: {
                    id: categoryID
                , }
                , dataType: 'json'
                , success: function(response) {
                    $('#sub_category').empty();
                    $('#sub_category').append('<option value="">Select a Sub Category</option>')

                    // $('#sub_category').find('option').not(':first').remove();    // above two lines alternative

                    $.each(response['subcategory'], function(key, item) {
                        // $('#sub_category').append("<option value="+item.id+">"+ item.name + "</option>")
                        $('#sub_category').append(`<option value=${item.id}> ${item.name} </option>`)

                    })
                }
                , error: function(jqXHR, exception) {
                    console.log('error');
                }
            })
        }

    })



    Dropzone.autoDiscover = false;
    const dropzone = $('#image').dropzone({
        url: "{{route('product-images.update')}}"
        , maxFiles: 10
        , paramName: 'image'
        , params: {
            'product_id': '{{$product->id}}'
        }
        , addRemoveFiles: "images/jpeg,image/png,image/gif"
        , headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        , success: function(file, response) {
            // $("#image_id").val(response.image_id)
            var html = `<div class="col-md-3" id="image-row-${response.image_id}">
                <div class="card">
                    <input type="hidden" name="image_array[]" value="${response.image_id}">
                    <img src="${response.ImagePath}" class="card-img-top" alt="...">
                    <div class="card-body">
                        <a href="javascript:void(0)" onclick="deleteImage(${response.image_id})" class="btn btn-danger">Delete</a>
                    </div>
                </div>
            </div`;

            $('#product-gallery').append(html)
        },

        complete: function(file) {
            this.removeFile(file);
        }
    })

    function deleteImage(id) {
        if (confirm('Are you sure you want to delete this?')) {
            $('#image-row-' + id).remove()
            $.ajax({
                url: "{{route('product-images.destroy')}}"
                , method: 'delete'
                , data: {
                    id: id
                }
                , success: function(response) {
                    if (response.status == 'true') {
                        $('#image-row-'+$id).remove();
                        console.log($('#image-row-'+$id).remove());

                        alert(response.message);

                    } else {
                        alert(response.message)
                    }
                }
                , error: function(jqXHR, exception) {
                    console.log('error')
                }
            })
        }
    }

</script>




@endsection
