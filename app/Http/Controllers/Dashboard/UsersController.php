<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequests;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;
use Auth;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:users_read'])->only('index');
        $this->middleware(['permission:users_create'])->only('create');
        // $this->middleware(['permission:users_delete'])->only('destroy');
        $this->middleware(['permission:users_update'])->only(['edit', 'status', 'destroy']);
        $this->middleware(['role:vendor'])->only(['createEmployee', 'storeEmployee', 'indexEmployee', 'editEmployee', 'updateEmployee']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('panel.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('panel.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequests $request)
    {
        // 1- generate hash password
        $password = Hash::make($request->password);
        // 2- append first data to user only email data
        $user = $request->only(['email', 'name', 'lat', 'long', 'address']);
        // 3- append all data to user
        $dataUser = array_merge($user, ['password' => $password]);
        // 4- create user
        $user = User::create($dataUser);

        toastr()->success(__('alert.successCreated'), __('alert.accept'));
        return redirect()->route('users.permission', $user->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $user = User::find($id);
        if ($user) {
            return view('panel.user.edit', compact('user'));
        }
        toastr()->info(__('alert.errorMessage'), __('alert.cancel'));
        return view('panel.user.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequests $request, $id)
    {
        // get user from database
        $user = User::find($id);
        // 2- check user is exists
        if ($user) {
            // 3- append first data to user only email data
            $dataUser = $request->only(['email', 'name', 'lat', 'long', 'address']);
            if(isset($request->password)) {
                // 4- generate hash password
                $password = Hash::make($request->password);
                // 5- append all data to user
                $dataUser = array_merge($dataUser, ['password' => $password]);
            }
            // 6- create user
            $user->update($dataUser);
            // 7- update email from vendors table
            if($user->store) {
                $vendor = Vendor::where('user_id', $id)->first();
                $vendor->update(['email' => $request->email]);
            }

            // 8- message and redirect
            toastr()->success(__('alert.successCreated'), __('alert.accept'));
            return view('panel.user.edit', compact('user'));
        }
        // 9- failed to find
        toastr()->info(__('alert.errorMessage'), __('alert.cancel'));
        return view('panel.user.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return response()->json([
                'success' => TRUE,
                'message' => __('alert.successUpdate')
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => __('alert.successUpdate')
            ]);
        }
    }

    public function status(Request $request)
    {
        //
        if ($request->pk_i_id == NULL) {
            return response()->json([
                'success' => False,
                'message' => __('alert.errorMessage')
            ]);
        }
        $id = $request->pk_i_id;
        $update = User::withTrashed()->find($id);
        if ($update) {
            if ($update->deleted_at) {
                $update->restore();
            } else {
                $update->delete();
            }
            return response()->json([
                'success' => TRUE,
                'message' => __('alert.successUpdate')
            ]);
        }
    }

    public function datatable(Request $request)
    {
        //
        $data = $request->all();
        $s_name = $request->get('s_name');
        $s_mobile_number = $request->get('s_mobile_number');
        $s_email = $request->get('s_email');

        $b_enabled = $request->get("b_enabled");

        $objects = User::withTrashed();
        if(isset($s_name) && strlen($s_name) > 0)
            $objects = $objects->where('name', 'LIKE', $s_name.'%');

        if(isset($b_enabled) && $b_enabled == 1)
            $objects = $objects->whereNull('deleted_at');

        if(isset($b_enabled) && $b_enabled == 2)
            $objects = $objects->onlyTrashed();

        return DataTables::of($objects)
            ->addColumn('id', function ($object) {
                return $object->id;
            })
            ->addColumn('s_name', function ($object) {
//                return app()->getLocale();
                return $object->name;
            })
            ->addColumn('email', function ($object) {
//                return app()->getLocale();
                return $object->email;
            })
            ->addColumn('b_enabled', function ($object) {
                $is_enabled = '';
                $is_enabled .= '<div class="col-12">';
                $is_enabled .= '<span class="switch switch-sm switch-outline switch-icon switch-primary"><label>';
                if ($object->deleted_at == '') {
                    $is_enabled .= '<input onclick="ch_st(' . $object->id . ',\'' . route('users.status') . '\',\'' . csrf_token() . '\')" type="checkbox" checked="checked" name="select" />';
                } else {
                    $is_enabled .= '<input onclick="ch_st(' . $object->id . ',\'' . route('users.status') . '\',\'' . csrf_token() . '\')" type="checkbox" name="select" />';
                }
                $is_enabled .= '<span></span></label>';
                $is_enabled .= '</span>';
                $is_enabled .= '</div>';
                return $is_enabled;
            })

            ->addColumn('actions', function ($object) {
                $action = '';
                if(Auth::user()->hasPermission('users_update')) {
                    $action .= '<div class="dropdown dropdown-inline">
					<a href="javascript:;" class="btn btn-sm btn-default btn-text-primary btn-hover-primary btn-icon" data-toggle="dropdown">
						<span class="svg-icon svg-icon-md">
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="svg-icon">
								<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
									<rect x="0" y="0" width="24" height="24"/>
									<path d="M7,3 L17,3 C19.209139,3 21,4.790861 21,7 C21,9.209139 19.209139,11 17,11 L7,11 C4.790861,11 3,9.209139 3,7 C3,4.790861 4.790861,3 7,3 Z M7,9 C8.1045695,9 9,8.1045695 9,7 C9,5.8954305 8.1045695,5 7,5 C5.8954305,5 5,5.8954305 5,7 C5,8.1045695 5.8954305,9 7,9 Z" fill="#000000"/>
									<path d="M7,13 L17,13 C19.209139,13 21,14.790861 21,17 C21,19.209139 19.209139,21 17,21 L7,21 C4.790861,21 3,19.209139 3,17 C3,14.790861 4.790861,13 7,13 Z M17,19 C18.1045695,19 19,18.1045695 19,17 C19,15.8954305 18.1045695,15 17,15 C15.8954305,15 15,15.8954305 15,17 C15,18.1045695 15.8954305,19 17,19 Z" fill="#000000" opacity="0.3"/>
								</g>
							</svg>
						</span>
					</a>
					<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
						<ul class="navi flex-column navi-hover py-2">
							<li class="navi-header font-weight-bolder text-uppercase font-size-xs text-primary pb-2">
								'.trans("table.action_title_tabe").':
							</li>
							<li class="navi-item">
								<a href="'.route("users.edit", $object->id).'" class="navi-link">
									<span class="navi-icon"><i class="la la-list-alt"></i></span>
									<span class="navi-text">'.trans("table.action_edit").'</span>
								</a>
							</li>
						</ul>
					</div>
				</div>';
                }
                if(Auth::user()->hasPermission('users_permission')) {
                    $action .= '
                <a href="'.route("users.permission", $object->id).'" class="btn btn-sm btn-default btn-text-primary btn-hover-primary btn-icon" title="'.@trans("role.permission").'">
                <span class="navi-icon"><i class="fas fa-unlock-alt"></i></span>
                            </a>
                ';
                }
                return $action;
            })
            ->addColumn('dt_creation_date', function ($object) {
                return Carbon::parse($object->created_at)->toDateString();
            })
            ->addColumn('type', function ($object) {
                if($object->store()->exists()) {
                    return __('users.vendor');
                } elseif($object->delivery()->exists()) {
                    return __('users.driver');
                } elseif ($object->vendor_id != null){
                    return __('store.employee');
                } else {
                    return __('users.admin');
                }
            })
            ->addIndexColumn()
            ->rawColumns(['actions', 'b_enabled'])
            ->make(true);
    }

    public function permission($id) {
        $role = Role::where('name', '<>', 'vendor')->get();
        $permission = Permission::where('active', null)->get();
        $user = User::find($id);

        //return $user;
        if(!$user) {
            toastr()->info(__('alert.errorMessage'), __('alert.cancel'));
            return redirect()->route('users.index');
        }
        //
        return view('panel.user.permission', compact('permission', 'role', 'id', 'user'));
    }

    public function setPermission(Request $request){
        // validation check data format is true or not
        $validator = $this->validate($request, [
            'user' => 'required|exists:users,id',
            'role' => 'array|nullable',
            'permission' => 'array|nullable'
        ]);
        // get user from database
        $user = User::query()->find($request->user);

        // check user is exists
        if(!$user)
        {
            toastr()->info(__('alert.errorMessage'), __('alert.cancel'));
            return redirect()->route('user.index');
        }

        // sync roles to user
        if($request->role) {
            $user->syncRoles($request->role);
        }else
            $user->syncRoles([]);

        // sync permissions to user
        if($request->permission) {
            $user->syncPermissions($request->permission);
        }else
            $user->syncPermissions([]);

        // redirect to index table user
        toastr()->success(__('alert.successCreated'), __('alert.accept'));
        return redirect()->route('users.permission',$user->id);
    }
    
    public function indexEmployee() {
        return view('panel.employee.index');
    }
    
    public function createEmployee() {
        return view('panel.employee.create');
    }
    
    public function storeEmployee(UserRequests $request)
    {
        // 1- generate hash password
        $password = Hash::make($request->password);
        // 2- vendor id 
        $vendoreId = Auth::user()->stores->id;
        // 3- append first data to user only email data
        $user = $request->only(['email', 'name', 'lat', 'long', 'address']);
        // 4- append all data to user
        $dataUser = array_merge($user, ['password' => $password, 'vendor_id' => $vendoreId]);
        // 5- create user
        $user = User::create($dataUser);
        $user->syncRoles(['employee']);
        toastr()->success(__('alert.successCreated'), __('alert.accept'));
        return redirect()->route('users.index.vendor');
    }
    
    public function editEmployee($id) {
       $user = User::find($id);
       if(!$user) {
           toastr()->info(__('alert.errorMessage'), __('alert.cancel'));
            return redirect()->route('users.index.vendor');
       }
       return view('panel.employee.edit', compact('user'));
    }
    
    public function updateEmployee(Request $request) {
        // get user from database
        $user = User::find($request->user_id);
        $request->validate([
            'name' => 'required|max:255',
            'email'       => 'required|email|max:50|unique:users,email,'.$user->id,
            'lat' => 'required',
            'long' => 'required',
            'address' => 'required',
            'password' => 'max:255|min:6|nullable',
        ]);
        // 2- check user is exists
        if ($user) {
            // 3- append first data to user only email data
            $dataUser = $request->only(['email', 'name', 'lat', 'long', 'address']);
            if(isset($request->password)) {
                // 4- generate hash password
                $password = Hash::make($request->password);
                // 5- append all data to user
                $dataUser = array_merge($dataUser, ['password' => $password]);
            }
            // 6- create user
            $user->update($dataUser);

            // 7- message and redirect
            toastr()->success(__('alert.successCreated'), __('alert.accept'));
            return redirect()->route('users.index.vendor');
        }
        // 9- failed to find
        toastr()->info(__('alert.errorMessage'), __('alert.cancel'));
        return redirect()->route('users.index.vendor');
    }
    
    public function datatableEmployee(Request $request)
    {
        //
        $data = $request->all();
        $s_name = $request->get('s_name');
        $s_mobile_number = $request->get('s_mobile_number');
        $s_email = $request->get('s_email');

        $b_enabled = $request->get("b_enabled");

        $objects = User::where('vendor_id', Auth::user()->stores->id);
        if(isset($s_name) && strlen($s_name) > 0)
            $objects = $objects->where('name', 'LIKE', $s_name.'%');

        if(isset($b_enabled) && $b_enabled == 1)
            $objects = $objects->whereNull('deleted_at');

        if(isset($b_enabled) && $b_enabled == 2)
            $objects = $objects->onlyTrashed();

        return DataTables::of($objects)
            ->addColumn('id', function ($object) {
                return $object->id;
            })
            ->addColumn('s_name', function ($object) {
//                return app()->getLocale();
                return $object->name;
            })
            ->addColumn('email', function ($object) {
//                return app()->getLocale();
                return $object->email;
            })
            ->addColumn('b_enabled', function ($object) {
                $is_enabled = '';
                $is_enabled .= '<div class="col-12">';
                $is_enabled .= '<span class="switch switch-sm switch-outline switch-icon switch-primary"><label>';
                if ($object->deleted_at == '') {
                    $is_enabled .= '<input onclick="ch_st(' . $object->id . ',\'' . route('users.status') . '\',\'' . csrf_token() . '\')" type="checkbox" checked="checked" name="select" />';
                } else {
                    $is_enabled .= '<input onclick="ch_st(' . $object->id . ',\'' . route('users.status') . '\',\'' . csrf_token() . '\')" type="checkbox" name="select" />';
                }
                $is_enabled .= '<span></span></label>';
                $is_enabled .= '</span>';
                $is_enabled .= '</div>';
                return $is_enabled;
            })

            ->addColumn('actions', function ($object) {
                $action = '<div class="dropdown dropdown-inline">
					<a href="javascript:;" class="btn btn-sm btn-default btn-text-primary btn-hover-primary btn-icon" data-toggle="dropdown">
						<span class="svg-icon svg-icon-md">
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="svg-icon">
								<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
									<rect x="0" y="0" width="24" height="24"/>
									<path d="M7,3 L17,3 C19.209139,3 21,4.790861 21,7 C21,9.209139 19.209139,11 17,11 L7,11 C4.790861,11 3,9.209139 3,7 C3,4.790861 4.790861,3 7,3 Z M7,9 C8.1045695,9 9,8.1045695 9,7 C9,5.8954305 8.1045695,5 7,5 C5.8954305,5 5,5.8954305 5,7 C5,8.1045695 5.8954305,9 7,9 Z" fill="#000000"/>
									<path d="M7,13 L17,13 C19.209139,13 21,14.790861 21,17 C21,19.209139 19.209139,21 17,21 L7,21 C4.790861,21 3,19.209139 3,17 C3,14.790861 4.790861,13 7,13 Z M17,19 C18.1045695,19 19,18.1045695 19,17 C19,15.8954305 18.1045695,15 17,15 C15.8954305,15 15,15.8954305 15,17 C15,18.1045695 15.8954305,19 17,19 Z" fill="#000000" opacity="0.3"/>
								</g>
							</svg>
						</span>
					</a>
					<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
						<ul class="navi flex-column navi-hover py-2">
							<li class="navi-header font-weight-bolder text-uppercase font-size-xs text-primary pb-2">
								'.trans("table.action_title_tabe").':
							</li>
							<li class="navi-item">
								<a href="'.route("users.edit.vendor", $object->id).'" class="navi-link">
									<span class="navi-icon"><i class="la la-list-alt"></i></span>
									<span class="navi-text">'.trans("table.action_edit").'</span>
								</a>
							</li>
						</ul>
					</div>
				</div>';
                return $action;
            })
            ->addColumn('dt_creation_date', function ($object) {
                return Carbon::parse($object->created_at)->toDateString();
            })
            ->addColumn('type', function ($object) {
                if($object->store()->exists()) {
                    return __('users.vendor');
                } elseif($object->delivery()->exists()) {
                    return __('users.driver');
                } elseif ($object->vendor_id != null){
                    return __('store.employee');
                } else {
                    return __('users.admin');
                }
            })
            ->addIndexColumn()
            ->rawColumns(['actions', 'b_enabled'])
            ->make(true);
    }
}
