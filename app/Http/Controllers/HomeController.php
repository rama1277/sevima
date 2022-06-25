<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Permission;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $permissions = Permission::where('user_id', Auth::user()->id )->get();

        $type = $permissions->pluck('type');

       
        $access = [];
        foreach($type as $data){
            $tmp = $permissions->where('type', $data);

            foreach ($tmp as $value) {
                $ak = json_decode($value['access'],true);
                $value->role = key($ak);
                $value->action = $ak[key($ak)];
            }
            $access[$data] = $tmp;
        }

        return view('home', [
            'posts' => Post::all(),
            'permissions' => $access
        ]);
    }
}
