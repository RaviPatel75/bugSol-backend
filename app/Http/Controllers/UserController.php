<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Lang;
use DataTables;


class UserController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','store']]);
        $this->middleware('permission:user-create', ['only' => ['create','store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $isSuperAdmin = (Auth::user()->roles[0]->name == 'Super Admin') ? true : false;
        $userId = (Auth::user()->roles[0]->name != 'Super Admin') ? Auth::user()->id : '0';

        if ($isSuperAdmin) {
            $data = User::orderBy('id','DESC')
                    ->where('created_by','=',$userId)
                    ->paginate(5);
        } else {
            $data = User::orderBy('id','DESC')
                        ->whereHas('roles', function ($query) {
                            $query->where('name','!=', 'Super Admin');
                        })
                        ->where('created_by','=',$userId)
                        ->paginate(5);
        }

        return view('users.index',compact('data'))
            ->with('i', ($request->input('page', 1) -1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $hasPermission = Auth::user()->hasPermissionTo('user-create');
        $isSuperAdmin = (Auth::user()->roles[0]->name == 'Super Admin') ? true : false;
        
        if ($hasPermission && $isSuperAdmin) {
            $roles = Role::pluck('name','name')->all();
        } else {
            $roles = array();
            $rolesQueryData = Role::where('name','!=', 'Super Admin')->get();
            foreach($rolesQueryData as $data)
            {
                $roles[$data->name] = $data->name;
            }
        }

        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        // add one field of created_by
        $isSuperAdmin = (Auth::user()->roles[0]->name == 'Super Admin') ? true : false;

        if ($isSuperAdmin) {
            $input['created_by'] = '0';
        } else {
            $input['created_by'] = Auth::user()->id;
        }
        
        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
                        ->with('success',Lang::get('user.created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $isSuperAdmin = (Auth::user()->roles[0]->name == 'Super Admin') ? true : false;

        if ($isSuperAdmin) {
            $data = User::orderBy('id','DESC')->paginate(5);
        } else {
            $data = User::orderBy('id','DESC')
                        ->whereHas('roles', function ($query) {
                            $query->where('name','!=', 'Super Admin');
                        })
                        ->paginate(5);
        }
        
        $user = User::find($id);
        $hasPermission = Auth::user()->hasPermissionTo('user-edit');

        if ($hasPermission && $isSuperAdmin) {
            $roles = Role::pluck('name','name')->all();
        } else {
            $roles = array();
            $rolesQueryData = Role::where('name','!=', 'Super Admin')->get();
            foreach($rolesQueryData as $data)
            {
                $roles[$data->name] = $data->name;
            }
        }

        $userRole = $user->roles->pluck('name','name')->all();

        return view('users.show',compact('user','roles','userRole'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $isSuperAdmin = (Auth::user()->roles[0]->name == 'Super Admin') ? true : false;

        if ($isSuperAdmin) {
            $data = User::orderBy('id','DESC')->paginate(5);
        } else {
            $data = User::orderBy('id','DESC')
                        ->whereHas('roles', function ($query) {
                            $query->where('name','!=', 'Super Admin');
                        })
                        ->paginate(5);
        }
        
        $user = User::find($id);
        $hasPermission = Auth::user()->hasPermissionTo('user-edit');

        if ($hasPermission && $isSuperAdmin) {
            $roles = Role::pluck('name','name')->all();
        } else {
            $roles = array();
            $rolesQueryData = Role::where('name','!=', 'Super Admin')->get();
            foreach($rolesQueryData as $data)
            {
                $roles[$data->name] = $data->name;
            }
        }

        $userRole = $user->roles->pluck('name','name')->all();
    
        return view('users.edit',compact('user','roles','userRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);
    
        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }
    
        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
    
        $user->assignRole($request->input('roles'));
    
        return redirect()->route('users.index')
                        ->with('success',Lang::get('user.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return User::find($id)->delete();
        // return redirect()->route('users.index')
        //                 ->with('success',Lang::get('user.deleted'));
    }

    public function getUsers(Request $request)
    {
        $isSuperAdmin = (Auth::user()->roles[0]->name == 'Super Admin') ? true : false;
        $userId = (Auth::user()->roles[0]->name != 'Super Admin') ? Auth::user()->id : '0';

        if($request->ajax()) {
            if ($isSuperAdmin) {
                $data = User::orderBy('id','DESC')
                        ->where('created_by','=',$userId)->get();
            } else {
                $data = User::orderBy('id','DESC')
                            ->whereHas('roles', function ($query) {
                                $query->where('name','!=', 'Super Admin');
                            })
                            ->where('created_by','=',$userId)->get();
            }
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('role', function($row){
                        $badge = '';
                        foreach($row->getRoleNames() as $ur)
                        {
                            $badge .= '<label class="badge badge-success">'.$ur.'</label>';
                        }
                        return $badge;
                    })
                    ->addColumn('action', function($row){
                            $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Show" data-id="'.$row->id.'" class="btn btn-primary btn-sm view_user"><i class="fa fa-eye"></i></a>';
                            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm edit_user"><i class="fa fa-edit"></i></a>';
                            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm delete_user"><i class="fa fa-trash"></i></a>';

                            return $btn;
                    })
                    ->rawColumns(['action','role'])
                    ->make(true);
        }
    }
}
