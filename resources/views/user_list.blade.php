@extends('layouts.app')

@section('css')
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Data User') }}</div>

                <div class="card-body">
                     <table class="table mb-4">
                        <thead>
                            <tr class="">
                                <th scope="col">Username</th>
                                <th scope="col">Email</th>
                                <th scope="col">Permission</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $data)
                            <tr>
                                <td>{{ $data->name }}</td>
                                <td>{{ $data->email }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary user-permission" data-id="{{$data->id}}"
                                             title="Setting Permission"> 
                                        <i class="fa-solid fa-cogs"></i>
                                    </button>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-danger user-delete" data-id="{{$data->id}}" title="Delete">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                    &nbsp;
                                    <button class="btn btn-sm btn-secondary user-edit" data-id="{{$data->id}}" title="Edit"> 
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center">
                        {!! $users->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Vertically centered modal -->
<div class="modal fade" id="userModal" tabindex="-1" role="dialog" data-backdrop="static"
     aria-labelledby="menuModalLabel" aria-hidden="true">
    <form id="formUser">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Form User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" value="" class="form-control">
                @csrf
                
                <div class="row mb-2 form-group">
                    <div class="col-md-4">
                        <label class="form-label mg-b-0">Name</label>
                    </div>
                    <!-- col -->
                    <div class="col-md-8 mg-t-5 mg-md-t-0">
                        <input type="text" name="name" class="form-control" placeholder="Name" />
                        <div class="invalid-feedback" style="font-size: 90%"></div>
                    </div>
                    <!-- col -->
                </div>

                <div class="row mb-2 form-group">
                    <div class="col-md-4">
                        <label class="form-label mg-b-0">Email</label>
                    </div>
                    <!-- col -->
                    <div class="col-md-8 mg-t-5 mg-md-t-0">
                        <input type="text" name="email" class="form-control" placeholder="Email"/>
                        <div class="invalid-feedback" style="font-size: 90%"></div>
                    </div>
                    <!-- col -->
                </div>
                <!-- row -->

                <div class="row mb-2">
                    <div class="col-md-4">
                        <label class="form-label mg-b-0">Password</label>
                    </div>
                    <!-- col -->
                    <div class="col-md-8 mg-t-5 mg-md-t-0">
                        <input type="password" name="password" class="form-control" placeholder="Password" />
                        <div class="invalid-feedback" style="font-size: 90%"></div>
                    </div>
                    <!-- col -->
                </div>
                <!-- row -->
            </div>
            <div class="modal-footer">
                <div class="col-md-12 text-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary user-save">Save</button>
                </div>
            </div>
        </div>
    </div>
    </form>
</div>

<!-- Vertically centered modal -->
<div class="modal fade" id="permissionModal" tabindex="-1" role="dialog" data-backdrop="static"
     aria-labelledby="menuModalLabel" aria-hidden="true">
    <form id="formPermission">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Form Permission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" value="" class="form-control">
                <input type="hidden" name="user_id" id="user_id" value="" class="form-control">
                @csrf

                <div class="row mb-2 form-group">
                    <div class="col-md-2"></div>
                    <div class="col-md-8 mg-t-5 mg-md-t-0">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input chk-access" type="checkbox" name="permission[posting][create]" value="1">
                            <label class="form-check-label" for="inlineCheckbox1">Posting</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-2 form-group">
                    <div class="col-md-2"></div>
                    <div class="col-md-8 mg-t-5 mg-md-t-0">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input chk-access" type="checkbox" name="permission[comment][create]" value="1">
                            <label class="form-check-label" for="inlineCheckbox1">Komentar</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-2 form-group">
                    <div class="col-md-2"></div>
                    <div class="col-md-8 mg-t-5 mg-md-t-0">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input chk-access" type="checkbox" name="permission[like][create]" value="1">
                            <label class="form-check-label" for="inlineCheckbox1">Like</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-2 form-group">
                    <div class="col-md-2"></div>
                    <div class="col-md-8 mg-t-5 mg-md-t-0">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input chk-access" type="checkbox" name="permission[user][create]" value="1">
                            <label class="form-check-label" for="inlineCheckbox1">Setting User</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-md-12 text-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary permission-save">Save</button>
                </div>
            </div>
        </div>
    </div>
    </form>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $( function() {
        $(".user-delete").click(function() {
            if( confirm('Hapus data user ?') ){
                id= $(this).data("id");
                url = '{{ url("/user/delete")  }}/'+id;
                $.getJSON(url, function(result){
                    if(result.error == 0){
                        alert(result.message);
                        location.reload();
                    }else{
                        alert('System Error');
                    }   
                });
            }
        });

        $(".user-edit").click(function() {
            id= $(this).data("id");
            url = '{{ url("/user/edit")  }}/'+id;
            $.getJSON(url, function(result){
                if(result.error == 0){
                    $('.form-control').val('');
                    $.each(result.record, function(index, value) {
                        $('[name ="' + index + '"]').val(value);
                    });

                    $('#userModal').modal("show");
                }else{
                    alert('System Error');
                }   
            });
        });

        $(".user-save").click(function() {
            data = $('#formUser').serialize(); 
            $('.form-control').removeClass('is-invalid');
            $.post("{{ route('user.save') }}", data, function(result){
                if(result.error == 0){
                    alert(result.message);
                    location.reload();
                }else{
                    if(result.code == 'validation'){
                        $.each(result.message, function( index, value ) {
                            $("[name='"+index+"']").addClass('is-invalid').next().html(value);
                        });                        
                    }
                    alert('System Error');
                }
            });
        });

        $(".user-permission").click(function() {
            $('.form-control').val('');
            $('.chk-access').prop('checked', false);

            id  = $(this).data("id");
            $('#user_id').val(id);
            url = '{{ url("/user/permission")  }}/'+id;
            $.getJSON(url, function(result){
                if(result.error == 0){
                    $.each(result.record, function(index, value) {

                        //permission[posting][create]

                        js  = JSON.parse(value.access);
                        key = Object.keys(js);
                        $('[name ="permission[' + value.type + ']['+ key+']"').prop('checked', js[key]);
                    });

                    $('#permissionModal').modal("show");
                }else{
                    alert('System Error');
                }   
            });
        });

        $(".permission-save").click(function() {
            data = $('#formPermission').serialize(); 
            $('.form-control').removeClass('is-invalid');
            $.post("{{ route('user.permission.save') }}", data, function(result){
                if(result.error == 0){
                    alert(result.message);
                    location.reload();
                }else{
                    if(result.code == 'validation'){
                        $.each(result.message, function( index, value ) {
                            $("[name='"+index+"']").addClass('is-invalid').next().html(value);
                        });                        
                    }
                    alert('System Error');
                }
            });
        });
    });
</script>
@endsection