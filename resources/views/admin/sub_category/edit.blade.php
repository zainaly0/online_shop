@extends('admin.layouts.app')

@section('content')

<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Sub Category</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('sub-categories.index')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">

        <form action="" name="subCategoryForm" id="subCategoryForm">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Category</label>
                                <select name="category" id="category" class="form-control">
                                    <option value="">Select a category</option>

                                    @if ($categories->isNotEmpty())
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{($category->id == $subCategory->category_id) ? 'selected' : ''}}>{{ $category->name }}</option>
                                    @endforeach
                                    @endif

                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Name" value="{{$subCategory->name}}">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email">Slug</label>
                                <input type="text" name="slug" id="slug" class="form-control" placeholder="Slug" value="{{$subCategory->slug}}">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1" {{$subCategory->status == "1" ? 'selected' : ''}}>Active</option>
                                    <option value="0" {{$subCategory->status == "0" ? 'selected' : ''}}>Block</option>
                                </select>
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" for="showHome">Show on Home</label>
                                <select name="showHome" id="showHome" name="showHome" class="form-control">
                                    <option value="Yes"  {{$subCategory->showHome == "Yes" ? "selected" : ""}}>Yes</option>
                                    <option value="No"  {{$subCategory->showHome == "No" ? "selected" : ""}}>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('sub-categories.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>

    </div>
    <!-- /.card -->
</section>



@endsection
@section('customJs')
<script>


$('#subCategoryForm').submit(function(event) {
        event.preventDefault();
        var element = $(this);
        // var element = $('#subCategoryForm')  //we can write this also in place of above code
        $('button[type=submit]').prop('disabled', true);
        var Url = " {{ route('sub-categories.update', $subCategory->id) }} ";
     
        $.ajax({
            url: Url
            , type: 'put'
            , data: element.serializeArray()
            , dataType: 'json'
            , success: function(response) {
                if (response["status"] === true) {
                    $('button[type=submit]').prop('disabled', false);
                    window.location.href= "{{route('sub-categories.index')}}"
                    $('#name').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                    $('#slug').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                    $('#category').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                    $('#status').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                } else {

                    if(response['notFound'] == true){
                        window.location.href="{{route('sub-categories.index')}}";
                        return false;
                    }
                    
                    var errors = response['errors'];
                    if (errors['name']) {
                        $('#name').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['name']);
                    }
                    if (errors['slug']) {
                        $('#slug').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['slug']);
                    }
                    if (errors['category']) {
                        $('#category').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['category']);
                    }
                    if (errors['status']) {
                        $('#status').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['status']);
                    }
                }
            }
            , error: function(jqXHR, exception) {
                $('button[type=submit]').prop('disabled', false);
                console.log('AJAX error:', exception);
            }
        });
    });





    $('#name').on('keyup', function() {
        var element = $(this);
        $('button[type=submit]').prop('disabled', true)
        $.ajax({
            url: '{{route("getSlug")}}'
            , method: 'get'
            , data: {
                title: element.val()
            }
            , success: function(response) {
                $('#slug').val(response.slug)
                $('button[type=submit]').prop('disabled', false)
            }
            , error: function(jqXHR, exception) {
                console.log('error')
            }
        })
    })

</script>
@endsection
