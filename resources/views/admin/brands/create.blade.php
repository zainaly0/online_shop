@extends('admin.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Brand</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('brand.index')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="" name="brandForm" id="createBrandForm" method="post">


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
                                <label for="email">Slug</label>
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
                <button class="btn btn-primary">Create</button>
                <a href="{{route('brand.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>



        </form>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection
@section('customJs')
<script>
    $('#createBrandForm').submit(function(event) {
        event.preventDefault();
        var element = $(this);
        $('button[type=submit]').prop('disabled', true);


        $.ajax({
            url: "{{ route('brand.store' ) }}"
            , method: 'POST'
            , data: element.serializeArray()
            , dataType: 'json'
            , success: function(response) {
                if(response['status'] == true){
                    window.location.href ="{{route('brand.index')}} ";
                }else{

                    var errors = response['errors']

                    if(errors['name']){
                        $('#name').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['name']);
                    }
                    if(errors['slug']){
                        $('#slug').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['slug']);
                    }
                    if(errors['status']){
                        $('#status').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['status']);
                    }

                }
            }
            , error: function(jqXHR, exception) {
                console.log('error')
            }
        })

    })



   $('#name').on('keyup', function(){
    var element = $(this); 
    $('button[type=submit]').prop('disabled', 'true')

    $.ajax({
        url: "{{route('getSlug')}}",
        method: 'get',
        data: {
            title: element.val()
        },
        success: function(response){
            $('#slug').val(response.slug) 
            $('#button[type=submit]').prop('disabled', false)
        },
        error: function(jqXHR, exception){
            console.log('error')
        }
        
    })

   })
</script>
@endsection
