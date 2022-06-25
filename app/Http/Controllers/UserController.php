<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Permission;

use Validator;
use Response;
use Session;
use Input;
use Exception;
use Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
        return view('user_list',[
            'users' => User::paginate(10)
        ]);
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
        $rules = [
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users,email,'.$input['id'],
            'password'  => 'required|string|min:8',
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
            $user = User::firstOrNew(['id' => $input['id']]);
            $user->name         = $input['name'];
            $user->email        = $input['email'];
            $user->password     = Hash::make($input['password']);
            $user->save();

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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     */
    public function edit($id)
    {
        try {
            $id = empty($id) ? 0 : $id;

            $user = user::where('id', $id)->first();
            if ($user === null && empty($user))
                throw new Exception("Data not found");

            return Response::json([
                'error'     => 0,
                'record'    => [
                    'id'        => $user->id,
                    'name'      => $user->name,
                    'email'     => $user->email,
                ]
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
     * Remove the specified resource from storage.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            \DB::beginTransaction();

            $user = User::where('id', $id)->first();
            if ($user === null && empty($user))
                throw new Exception("Data not found");

            $user->delete();

            Permission::where('user_id', $id)->delete();

            \DB::commit();
            return Response::json([
                'error'     => 0,
                'message'   => 'Data berhasil dihapus',
                'code'      => ''
            ]);
        } catch (\Exception $ex) {
            \DB::rollBack();
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
    public function permission($id)
    {
        try {
            $permission = Permission::where('user_id', $id)->get();
            return Response::json([
                'error'     => 0,
                'record'    => $permission->toArray()
                
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
    public function storePermission(Request $request)
    {
        $input = $request->input();

        try {
            \DB::beginTransaction();
            //delete data
            Permission::where('user_id', $input['user_id'])->delete();

            //insert data
            if( !empty($input['permission']) ) {
                foreach ($input['permission'] as $type => $data) {
                    Permission::create([
                        'user_id' => $input['user_id'],
                        'type'    => $type,
                        'access'  => json_encode($data),
                    ]);
                }
            }
            \DB::commit();

            return Response::json([
                'error'     => 0,
                'message'   => 'Data saved',
                'code'      => ''
            ]);
        } catch (\Exception $ex) {
            \DB::rollBack();
            return Response::json([
                'error'    => 1,
                'message'  => $ex->getMessage(),
                'line'     => $ex->getLine(),
                'code'     => 'other'
            ]);
        }
    }
}
