@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-10">
                            <h5 class="mt-1">{{ __('Status') }}</h5>
                            @php 
                                $create = (object)[];
                                if( !empty( $permissions['posting'] ) ){
                                    $post = $permissions['posting'];
                                    $create = $post->where('role', 'create')->first();
                                }
                            @endphp
                        </div>
                        <div class="col-md-2">
                            @if( !empty($create->action) )
                            <button class="btn btn-sm btn-primary  float-right post-add">Posting</button>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-md-12">
                    @forelse($posts as $post)
                        <div class="card mb-3">
                            @if( !empty($post->file_name) && file_exists(public_path('image/'.$post->file_name)) )
                            <img src="{{ asset('image/'.$post->file_name) }}" style="height: 300px;" class="card-img-top img-fluid" alt="...">
                            @endif

                            <div class="card-body">
                                <p class="card-text" style="text-align: justify;">{!! nl2br($post->content) !!}</p>
                            </div>

                            <ul class="list-group list-group-flush">
                                <li class="list-group-item list-group-item-secondary">
                                    <div class="hstack gap-2">
                                        <div class="text-primary"> 
                                            <small>
                                                {{ $post->comments->count() }}  

                                                @php 
                                                    $create = (object)[];
                                                    if( !empty( $permissions['comment'] ) ){
                                                        $comment = $permissions['comment'];
                                                        $create  = $comment->where('role', 'create')->first();
                                                    }
                                                @endphp
                                                <span style="cursor:pointer;" 
                                                    @if( !empty($create->action) )
                                                    onclick="commentPost('{{ $post->id}}')"
                                                    @endif
                                                > Komentar 
                                                </span>
                                            </small> 
                                        </div>

                                        <div class="vr"></div>
                                        
                                        <div class="text-primary"> 
                                            <small>
                                                {{ $post->suka->count() }} 

                                                @php 
                                                    $create = (object)[];
                                                    if( !empty( $permissions['like'] ) ){
                                                        $like = $permissions['like'];
                                                        $create  = $like->where('role', 'create')->first();
                                                    }
                                                @endphp
                                                <span style="cursor:pointer;" 
                                                    @if( !empty($create->action) )
                                                    onclick="likePost('{{ $post->id}}')"
                                                    @endif
                                                > Like 
                                                </span>
                                            </small> 
                                        </div>

                                        <div class="ms-auto">
                                            <small>{{ $post->user->name }}</small>
                                        </div>
                                        
                                        <div class="vr"></div>
                                        
                                        <div>
                                            <small>{{ $post->created_at->format('d/m/Y') }}</small>
                                        </div>
                                    </div>
                                </li>

                                @forelse($post->comments as $comment)
                                    @if( !empty($comment->user->name) )
                                    <li class="list-group-item">
                                        <p style="text-align: justify;">{!! nl2br($comment->note) !!}</p>

                                        <div class="hstack gap-2">
                                            <div class="ms-auto">
                                                <small>{{ $comment->user->name }}</small>
                                            </div>
                                            
                                            <div class="vr"></div>
                                            
                                            <div>
                                                <small>{{ $comment->created_at->format('d/m/Y') }}</small>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                @empty
                                <li class="list-group-item">
                                    <strong>Komentar kosong</strong>
                                </li>
                                @endforelse
                            </ul>
                        </div>
                    @empty
                        <div class="card">
                            <div class="card-body">
                                <p> <strong> Data Kosong</strong> </p>
                            </div>
                        </div>
                    @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Vertically centered modal -->
<div class="modal fade" id="userModal" tabindex="-1" role="dialog" data-backdrop="static"
     aria-labelledby="menuModalLabel" aria-hidden="true">
    <form id="formPost">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Form Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" value="" class="form-control">
                @csrf
                <div class="mb-3">
                    <textarea name="content"  class="form-control" placeholder="Konten" rows="4" style="resize: none"></textarea>
                    <div class="invalid-feedback" style="font-size: 90%"></div>
                </div>
                <div class="mb-3">
                    <div class="col-md-8 mg-t-5 mg-md-t-0">
                        <label class="btn btn-primary btn-sm" for="my-file-selector">
                            <input id="my-file-selector" type="file" style="display:none" name="files" 
                                    accept="image/x-png, image/jpeg" 
                                    onchange="$('#upload-file-info').text(this.files[0].name)">
                            <i class="fa fa-upload" aria-hidden="true"></i> Upload
                        </label>
                        <span class='label label-info' id="upload-file-info" style=""></span>
                        <div class="invalid-feedback" style="font-size: 90%"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-md-12 text-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary post-save">Save</button>
                </div>
            </div>
        </div>
    </div>
    </form>
</div>

<!-- Vertically centered modal -->
<div class="modal fade" id="commentModal" tabindex="-1" role="dialog" data-backdrop="static"
     aria-labelledby="menuModalLabel" aria-hidden="true">
    <form id="formComment">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Form Komentar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" value="" class="form-control">
                <input type="hidden" name="post_id" id="post_id" value="" class="form-control">
                @csrf
                <div class="mb-3">
                    <textarea name="note"  class="form-control" placeholder="Komentar" rows="4" style="resize: none"></textarea>
                    <div class="invalid-feedback" style="font-size: 90%"></div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-md-12 text-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary comment-save">Save</button>
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
        $(".post-add").click(function() {
            $('.form-control').val('');
            $('#upload-file-info').html('');
            $('#userModal').modal("show");
        });

        $(".post-save").click(function() {
            $('.form-control').removeClass('is-invalid');

            $this  = $('#formPost');
            url    = "{{ route('post.save') }}";
            data   = $this.serialize(); 
            dtype  = "POST";

            $.ajax({
                type: dtype,
                url:  url,
                data: new FormData($('#formPost')[0]),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(result) {
                    if(result.error == 0){
                        $('.form-control').val('');
                        alert('Data berhasil disimpan');
                        location.reload();
                    }else {
                        if(result.code == 'validation' || result.code == 'others'){
                            $.each(result.message, function( index, value ) {
                                $("[name='"+index+"']").addClass('is-invalid').next().html(value);
                            });                        
                        }
                        alert('System Error');
                    }
                },
                error: function(xhr, status, error) {
                   alert(false, "System Error");
                }
            });
        });


        $(".comment-save").click(function() { 
            $('.form-control').removeClass('is-invalid');

            $this  = $('#formComment');
            url    = "{{ route('post.comment.save') }}";
            data   = $this.serialize(); 
            dtype  = "POST";

            $.ajax({
                type: dtype,
                url:  url,
                data: new FormData($('#formComment')[0]),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(result) {
                    if(result.error == 0){
                        $('.form-control').val('');
                        alert('Data berhasil disimpan');
                        location.reload();
                    }else {
                        if(result.code == 'validation' || result.code == 'others'){
                            $.each(result.message, function( index, value ) {
                                $("[name='"+index+"']").addClass('is-invalid').next().html(value);
                            });                        
                        }
                        alert('System Error');
                    }
                },
                error: function(xhr, status, error) {
                   alert(false, "System Error");
                }
            });
        });
    });

    function likePost(id){
        $.getJSON('{{ url("/post/like")  }}/'+id, function(result){
            if(result.error == 0){
                alert(result.message);
                location.reload();
            }else{
                if( result.code == 'other')
                    alert(result.message);
                else
                    alert('System Error');
            }   
        });
    }

    function commentPost(id){
        $('#post_id').val(id);
        $('#commentModal').modal("show");
    }
</script>
@endsection