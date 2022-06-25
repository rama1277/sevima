<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;

use Validator;
use Response;
use Session;
use Input;
use Exception;
use Auth;
use File;

class PostController extends Controller
{
    public static $path;
    
    public function __construct()
    {
        self::$path = public_path('image/');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $file  = $request->file('files');

        $rules = [
            'content'  => 'required',
        ];

        $customMessages = [
            'content.required'   => "Konten tidak boleh kosong",
        ];

        if( !empty($file) ){
            $rules['files'] = 'mimes:jpg,png|max:5120';

            $customMessages['files.mimes'] = 'Tipe file dalam bentuk jpg';
            $customMessages['files.max' ]  = 'Ukuran file terlalu besar (Max 5MB)';
        }

        $validator  = Validator::make($input, $rules, $customMessages );
        if ($validator->fails()) {
            return Response::json([
                'error'    => 1,
                'message'  => $validator->errors(),
                'code'     => 'validation'
            ]);
        }

         if( !empty($file) && !$file->isValid()) {
            return Response::json([
                'error'    => 1,
                'message'  => 'File invalid',
                'code'     => 'others'
            ]);
        }

        try {
            \DB::beginTransaction();

            $post = Post::firstOrNew(['id' => $input['id']]);
            if( !empty($file) ){

                //check if create or edit
                $isUpdate = FALSE;
                if( !empty($post->file_name) ){
                    $isUpdate = TRUE;
                    $oldFile  = $post->file_name;
                }

                if( !File::isDirectory(self::$path) )
                    File::makeDirectory(self::$path, 0777);

                $filename = 'ab_'.substr( md5(mt_rand().date('ymd')), 0, 16).'.'.$file->getClientOriginalExtension();
                $file->move(self::$path,$filename);

                // jika edit data dengan file baru
                if( $isUpdate && !empty($file) ){
                    if( File::exists(self::$path.$post->file_name) )
                        File::delete(self::$path.$post->file_name);
                }
            }

            $post->content      = $input['content'];
            $post->file_name    = !empty($file) ? $filename : '' ;
            $post->created_by   = Auth::user()->id;
            $post->save();

            \DB::commit();
            return Response::json([
                'error'     => 0,
                'message'   => 'Data berhasil disimpan',
                'code'      => ''
            ]);
        } catch (\Exception $ex) {
            \DB::rollback();
            return Response::json([
                'error'    => 1,
                'message'  => $ex->getMessage(),
                'line'     => $ex->getLine(),
                'code'     => 'other'
            ]);
        }
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     */
    public function like($id)
    {
        try {
            $id = empty($id) ? 0 : $id;

            $likes = Like::where('post_id', $id)->where('user_id', Auth::user()->id)->get();
            if ( !empty($likes) && $likes->count() > 0 )
                throw new Exception("Anda sudah pernah like komentar");

            $like = Like::create([
                'post_id'   => $id,
                'user_id'   => Auth::user()->id
            ]);

            return Response::json([
                'error'     => 0,
                'message'   => 'Data berhasil disimpan',
                'code'      => ''
            ]);
        } catch (\Exception $ex) {
            return Response::json([
                'error'    => 1,
                'message'  => $ex->getMessage(),
                'line'     => $ex->getLine(),
                'code'     => 'other'
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeComment(Request $request)
    {
        $input = $request->all();
        $rules = [
            'note'      => 'required',
            'post_id'   => 'required'
        ];

        $validator  = Validator::make($input, $rules);
        if ($validator->fails()) {
            return Response::json([
                'error'    => 1,
                'message'  => $validator->errors(),
                'code'     => 'validation'
            ]);
        }

        try {
            $comment = Comment::firstOrNew(['id' => $input['id']]);
            $comment->note         = $input['note'];
            $comment->post_id      = $input['post_id'];
            $comment->created_by   = Auth::user()->id;
            $comment->save();

            return Response::json([
                'error'     => 0,
                'message'   => 'Data berhasil disimpan',
                'code'      => ''
            ]);
        } catch (\Exception $ex) {
            return Response::json([
                'error'    => 1,
                'message'  => $ex->getMessage(),
                'line'     => $ex->getLine(),
                'code'     => 'other'
            ]);
        }
    }
}
