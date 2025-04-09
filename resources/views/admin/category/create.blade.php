@extends('admin.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Category</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('categories.index')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        @include('admin.message')
        <form action="" method="post" id="categoryForm" name="categoryForm">

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Name">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Slug</label>
                                <input type="text" name="slug" id="slug" class="form-control" placeholder="Slug" readonly>
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" for="status">Status</label>
                                <select name="status" id="status" name="status" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">Block</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="#" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection
@section('customJs')
<script>
    $('#categoryForm').submit(function(event) {
        event.preventDefault();
        var element = $(this);
        $('button[type=submit]').prop('disabled', true);
        $.ajax({
            url: '{{ route("categories.store") }}'
            , type: 'post'
            , data: element.serializeArray()
            , dataType: 'json'
            , success: function(response) {
                if (response["status"] === true) {
                    $('button[type=submit]').prop('disabled', false);
                    window.location.href= "{{route('categories.index')}}"
                    $('#name').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                    $('#slug').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                } else {
                    var errors = response['errors'];
                    if (errors['name']) {
                        $('#name').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['name']);
                    }
                    if (errors['slug']) {
                        $('#slug').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['slug']);
                    }
                }
            }
            , error: function(jqXHR, exception) {
                console.log('AJAX error:', exception);
            }
        });
    });



    $('#name').on('keyup',function() {
        console.log('zaid')
        var element = $(this); 
        $('button[type=submit]').prop('disabled', true)
        $.ajax({
            url: '{{route("getSlug")}}'
            , method: 'get'
            , data: {
                title: element.val()
            }
            , success: function(response) {
                $('button[type=submit]').prop('disabled', false)
                $('#slug').val(response.slug)
            }
            , error: function(jqXHR, exception) {
                console.log('error')
            }
        })
    })

</script>

@endsection
