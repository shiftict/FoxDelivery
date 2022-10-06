<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\VendorRequests;
use App\Http\Resources\Vendor\VendorResource;
use App\Http\Resources\Vendor\VendorOptionResource;
use App\Models\Area;
use App\Models\AttachmentStores;
use App\Models\DeliveryTime;
use App\Models\User;
use App\Models\City;
use App\Models\CityVendors;
use App\Models\Vendor;
use App\Models\Drivers;
use App\Models\VendorListPackages;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use UploadImage;
use Yajra\DataTables\DataTables;
use App\Http\Resources\Delivery\Deliveryresource;
use App\Http\Resources\Delivery\DeliveryVendorresource;
use App\Models\Delivery;

class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:vendor_read'])->only('index');
        $this->middleware(['permission:vendor_create'])->only('create');
        $this->middleware(['permission:vendor_delete'])->only('destroy');
        $this->middleware(['permission:vendor_update'])->only('edit');
        $this->middleware(['role:vendor'])->only('setting');
        $this->middleware(['permission:vendor_update'])->only('showFile');
        $this->middleware(['role:employee'])->only('employeeVendors');
        $this->middleware(['role:employee'])->only('employeeDatatable');
    }

    protected $path_image = 'public/image/stores';
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('panel.store.index');
    }

    /**
     * get all vendor with package
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllVendorWithPackages(Request $request) {
        return 1;
    }

    /**
     * get all vendor with package (Peer Hours)
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllVendorForPeerHours(Request $request) {
        $vendors = Vendor::where('status', '1')->get();
        return VendorOptionResource::collection($vendors);
    }

    /*
    * setting view
    */
    public function setting() {
        $user = Auth::user();
        $city_id = $user->city->pluck('city_id');
        $city = City::whereIn('id', $city_id)->with(['area'])->get();
        $packagesWithExpierd = $user->packagesWithExpierd;
        $packages = $user->packages;
        return view('panel.store.setting', compact('city', 'packages', 'packagesWithExpierd'));
    }

    /*
     * get all vendor
     */
    public function getAllVendor() {
        $vendor = Vendor::with(['user'])->get();
        return VendorOptionResource::collection($vendor);
//        return $vendor;
    }

    /*
     * get all details vendor
     */
    public function getAllVendorDetails(Request $request) {
        $package = VendorListPackages::where('user_id', $request->id)
            ->where('status', '1')
            ->where('number_of_order', '>', '0')
            ->first();
        $packageNumber = VendorListPackages::where('user_id', $request->id)
            ->where('status', '1')
            ->get();
        $user = User::find($request->id);
//        return $packageNumber->count();
//        return $user;
        if($package && $user && $packageNumber->count() == 1) {
            return response()->json(['status' => 200, 'message' => $package->number_of_order, 'package' => 1, 'lat' => $user->lat, 'address' => $user->address]);
        } else if($package){
            return response()->json(['status' => 200, 'message' => $package->number_of_order, 'package' => 2, 'lat' => $user->lat, 'address' => $user->address]);
        } else {
            return response()->json(['status' => 444, 'message' => null, 'lat' => $user->lat, 'package' => 1, 'address' => $user->address]);
        }
    }

    /*
    * update password
    */
    public function updatePassword(Request $request) {
        $newPassword = Hash::make($request->password);
        $user = Auth::user()->update(['password' => $newPassword]);
        toastr()->success(__('alert.successUpdate'));
        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('panel.store.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
//    public function store(VendorRequests $request)
    public function store(Request $request)
    {
        app()->setLocale(\session()->get('locale'));
         $request->validate([
            'nameAr' => 'required|max:255',
            'code' => 'required|unique:users,code|max:50',
            'nameEn' => 'required|max:255',
            'phone'   => 'required|min:8|max:10',
            'password' => 'required|min:6|max:20',
            'payments' => 'required|in:1,0',
            'email'   => 'required|email|unique:users,email|max:50',
            'lat' => 'required|max:255',
            'long' => 'required|max:255',
            'package' => 'required|in:2,1,0',
            'status'  => 'required|in:1,0',
            // only create packages | package per hours
            'packageHours'    => 'array',
            'mainPackageHours.start'           => 'required_if:package,==,1',
            'mainPackageHours.end'             => 'after_or_equal:starts|nullable',
            'mainPackageHours.pricing'        => 'required_if:package,==,1|max:255',
            // only create packages | package per order
            'packageOrder'    => 'array',
            'packageOrder.pricing'         => 'required_if:package,==,0|max:255',
            'packageOrder.stock'           => 'required_if:package,==,0|max:255',
            'packageOrder.startL_order'    => 'required_if:package,==,0',
//            'packageOrder.endL_order'      => 'required_if:packageOrder.startL_order,!=,null|after_or_equal:startL_order',
            'packageOrder.endL_order'      => 'after_or_equal:startL_order|nullable',
        ]);
        DB::beginTransaction();
        // 1- generate hash password
        $password = Hash::make($request->password);
        // 2- append first data to store
        $store = $request->only(['phone', 'citys', 'email', 'status', 'lat', 'long', 'city', 'block', 'home', 'sabil', 'street', 'code']);
        // 3- append first data to user only email data
        $user = $request->only(['email', 'code']);
        // 4- append all data to user
        $dataUser = array_merge($user, ['password' => $password, 'mobile' => $request->phone, 'name' => $request->nameEn, 'lat' => $request->lat, 'long' => $request->long, 'address' => $request->city]);
        // 5- create user
        $user = User::create($dataUser);
        $permission =  $user->syncPermissions(['order_create', 'order_read', 'order_delete']);
        $user->syncRoles(['vendor']);
        // 6- append name of languages
        $name = [
            'ar' => $request->nameAr,
            'en' => $request->nameEn,
        ];
        // 7- append all data store
        $dataStore = array_merge($store, ['name' => $name, 'created_by' => Auth::id(), 'user_id' => $user->id]);
        // 8- create store
        $createStore = Vendor::create($dataStore);
        // 9- create file from session to store
        if (Session::get('file')){
            foreach (Session::get('file') as $key => $image){
                if (!is_null(Session::get('file')[$key])) {
                    AttachmentStores::query()->create([
                        'vendor_id' => $createStore->id,
                        'name'      => $image['name'],
                        'ext'       => $image['ext'],
                        'path'      => 'storage/image/stores/' . $image['name'],
                    ]);
                }
            }
            // 9- remove all file from session
            Session::forget('file');
            Session::remove('images');
        }
        // 9- order data at package
        if($request->package == 1) {
            // only package per hours
            $packageOrder = $request->mainPackageHours;
            // date format
            $startL_order = Carbon::parse($packageOrder['start'])->addHour();
            $endL_order = Carbon::parse($packageOrder['end'])->addHour();
            // append format data
            $pricing = $packageOrder['pricing'];
            // append new array for data list packages
            $dataPackagePerOrder = ['date_from' => $startL_order,
                'date_to' => $endL_order,
                'payment' => $request->payments,
                'vendor_id' => $createStore->id,
                'user_id' => $user->id,
                'driver_number' => $packageOrder['drivers'],
                'pricing' => $pricing];
            // initialize new vendor list packages
            $VendorListPackages = VendorListPackages::create($dataPackagePerOrder);
            // initialize time delivery
            // foreach from array
            foreach($request->packageHours as $key => $value) {
                // check data its not empty
                if($value['hours'] != null && $value['vehicle'] != null) {
                    // filter data from requests
//                    $start_first_shift = Carbon::parse($value['start_first_shift']);
//                    $end_first_shift = Carbon::parse($value['end_first_shift']);
//                    $start_secound_shift = Carbon::parse($value['start_secound_shift']);
//                    $end_secound_shift = Carbon::parse($value['end_secound_shift']);
//                    $drivers = $value['drivers'];
                    $vehicle = $value['vehicle'];
                    $hours = $value['hours'];
                    // format data for delivery
                    $formatData = ['hours' => $hours,
                        'vehicle' => $vehicle,
                        'vendor_id' => $createStore->id,
                        'vendor_list_package_id' => $VendorListPackages->id];
                    // initialize time delivery
                    $createTimeDelivry = DeliveryTime::create($formatData);
                }
            }
//            $timedelivry = DeliveryTime::create();
        } elseif ($request->package == 0) {
            // only package per orders
            $packageOrder = $request->packageOrder;
            // date format
            $startL_order = Carbon::parse($packageOrder['startL_order'])->addHour();
            $endL_order = Carbon::parse($packageOrder['endL_order'])->addHour();
            // append format data
            $pricing = $packageOrder['pricing'];
            $stock = $packageOrder['stock'];
            // append new array for data list packages
            $dataPackagePerOrder = ['date_from' => $startL_order,
                'date_to' => $endL_order,
                'vendor_id' => $createStore->id,
                'pricing' => $pricing,
                'payment' => $request->payments,
                'user_id' => $user->id,
                'number_of_order' => $stock];
            // initialize new vendor list packages
            $VendorListPackages = VendorListPackages::create($dataPackagePerOrder);
        } else if($request->package == 2){
            // only package per hours
            $packageOrder = $request->mainPackageHours;
            // date format
            $startL_order = Carbon::parse($packageOrder['start'])->addHour();
            $endL_order = Carbon::parse($packageOrder['end'])->addHour();
            // append format data
            $pricing = $packageOrder['pricing'];
            // append new array for data list packages
            $dataPackagePerOrder = ['date_from' => $startL_order,
                'date_to' => $endL_order,
                'payment' => $request->payments,
                'vendor_id' => $createStore->id,
                'driver_number' => $packageOrder['drivers'],
                'user_id' => $user->id,
                'pricing' => $pricing];
            // initialize new vendor list packages
            $VendorListPackages = VendorListPackages::create($dataPackagePerOrder);
            // initialize time delivery
            // foreach from array
            foreach($request->packageHours as $key => $value) {
                // check data its not empty
                if($value['hours'] != null && $value['vehicle'] != null) {
                    // filter data from requests
//                    $start_first_shift = Carbon::parse($value['start_first_shift']);
//                    $end_first_shift = Carbon::parse($value['end_first_shift']);
//                    $start_secound_shift = Carbon::parse($value['start_secound_shift']);
//                    $end_secound_shift = Carbon::parse($value['end_secound_shift']);
//                    $drivers = $value['drivers'];
                    $vehicle = $value['vehicle'];
                    $hours = $value['hours'];
                    // format data for delivery
                    $formatData = ['hours' => $hours,
                        'vehicle' => $vehicle,
                        'vendor_id' => $createStore->id,
                        'vendor_list_package_id' => $VendorListPackages->id];
                    // initialize time delivery
                    $createTimeDelivry = DeliveryTime::create($formatData);
                }
            }
            // only package per orders
            $packageOrder = $request->packageOrder;
            // date format
            $startL_order = Carbon::parse($packageOrder['startL_order'])->addHour();
            $endL_order = Carbon::parse($packageOrder['endL_order'])->addHour();
            // append format data
            $pricing = $packageOrder['pricing'];
            $stock = $packageOrder['stock'];
            // append new array for data list packages
            $dataPackagePerOrder = ['date_from' => $startL_order,
                'date_to' => $endL_order,
                'vendor_id' => $createStore->id,
                'pricing' => $pricing,
                'payment' => $request->payments,
                'user_id' => $user->id,
                'number_of_order' => $stock];
            // initialize new vendor list packages
            $VendorListPackages = VendorListPackages::create($dataPackagePerOrder);
        }
        DB::commit();
        return 1;
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
        $vendor = Vendor::query()->find($id);
        if(!$vendor)
        {
            toastr()->info(__('alert.errorMessage'), __('alert.cancel'));
            return redirect()->route('vendor.index');
        }
        $vendor = new VendorResource($vendor);
        return response()->json(['vendor' => $vendor]);
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
        $vendor = Vendor::query()->find($id);
        if(!$vendor)
        {
            toastr()->info(__('alert.errorMessage'), __('alert.cancel'));
            return redirect()->route('vendor.index');
        }
        return view('panel.store.edit', compact('vendor'));
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
                //app()->setLocale(\session()->get('locale'));
         $request->validate([
            'nameAr' => 'required_if:nameEn,==,null|max:255',
            'nameEn' => 'required_if:nameAr,==,null|max:255',
            'phone'   => 'required|min:8|max:10',
            'lat' => 'required|max:255',
            'long' => 'required|max:255',
            'status'  => 'required|in:1,0',
        ]);
        $createStore = Vendor::find($id);
        if(!$createStore)
            return redirect()->route('vendor.index');
        //
        // DB::beginTransaction();
        // 2- append first data to store
        $store = $request->only(['phone', 'citys', 'status', 'lat', 'long', 'block', 'home', 'sabil', 'street']);
        // 6- append name of languages
        $name = [
            'ar' => $request->nameAr,
            'en' => $request->nameEn,
        ];
        // 7- append all data store
        $dataStore = array_merge($store, ['name' => $name, 'city' => $request->cityy]);
        // 8- create store
        $createStore->update($dataStore);
        return 1;
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
        $vendor = Vendor::find($id);
        if ($vendor) {
            $vendor->delete();
            $vendor->user->delete();
            $vendor->get_list_days()->delete();
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

    /**
     * uploade file
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function showFile($id) {
         $attachment = AttachmentStores::where('vendor_id', $id)->get();
         return view('panel.store.file', compact('attachment', 'id'));//$attachment;
     }

     public function editFile(Request $request) {
         if($request->store) {
             $vendor = Vendor::find($request->store);
             if($vendor) {
                 if (Session::get('file')){
                    foreach (Session::get('file') as $key => $image){
                        if (!is_null(Session::get('file')[$key])) {
                            AttachmentStores::query()->create([
                                'vendor_id' => $vendor->id,
                                'name'      => $image['name'],
                                'ext'       => $image['ext'],
                                'path'      => 'storage/image/stores/' . $image['name'],
                            ]);
                        }
                    }
                    Session::forget('file');
                    Session::remove('images');
                }
                toastr()->success(__('alert.successUpdate'));
                return redirect()->back();
             }
             toastr()->error(__('alert.errorMessage'));
             return redirect()->back();
         }
         toastr()->error(__('alert.errorMessage'));
         return redirect()->back();
     }

     public function deleteFile($id) {
         $at = AttachmentStores::find($id);
         if($at) {
             $at->delete();
             toastr()->info(__('alert.deleteSuccess'));
             return redirect()->back();
         }
         toastr()->info(__('alert.errorMessage'));
         return redirect()->back();
     }

    /**
     * uploade file
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function uploade(Request $request)
    {
        // 1- check request have file
        if ($request->hasFile('file')){
            // 2- call uploade function
            $path = UploadImage::upload_image($request->file, $this->path_image);
            // 3- format json
            $data = json_decode(json_encode($path), true);
            // 4- extension file
            $ext  = $data['original']['ext'];
            // 5- name file
            $name = $data['original']['name'];
            // 6- push file data to array
            $array = ['ext' => $ext, 'name' => $name];
            // 7- push to session file
            Session::push('file', $array);
        }
    }

    /**
     * uploade file
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function city($id) {
        $vendor = Vendor::query()->with(['user'])->find($id);
        if(!$vendor)
        {
            toastr()->info(__('alert.errorMessage'), __('alert.cancel'));
            return redirect()->route('vendor.index');
        }
        $area = Area::where('status', '1')->get();
        return view('panel.store.city', compact('id', 'area', 'vendor'));
    }

    /**
     * add city to vendor
     *
     * @return \Illuminate\Http\Response
     */
    public function addCity(Request $request) {
        $vendor = Vendor::query()->find($request->user);
        if(!$vendor)
        {
            toastr()->info(__('alert.errorMessage'), __('alert.cancel'));
            return redirect()->route('vendor.index');
        }
        $user = User::find($request->user_id);
        if(!$user)
        {
            toastr()->info(__('alert.errorMessage'), __('alert.cancel'));
            return redirect()->route('vendor.index');
        }
        $user->city()->delete();
        if($request->city) {
            foreach($request->city as $city) {
                CityVendors::create([
                    'vendor_id' => $request->user,
                    'user_id' => $request->user_id,
                    'city_id' => $city,
                    ]);
            }
        }
        toastr()->success(__('alert.successCreated'), __('alert.success'));
        return redirect()->route('vendor.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
        $update = Vendor::find($id);
        if ($update) {
            if ($update->status) {
                $update->update(['status' => '0']);
            } else {
                $update->update(['status' => '1']);
            }
            return response()->json([
                'success' => TRUE,
                'message' => __('alert.successUpdate')
            ]);
        }
    }

    /**
     * datatable yajra
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function datatable(Request $request)
    {
        //
        $data = $request->all();
        $s_name = $request->get('s_name');
        $s_mobile_number = $request->get('s_mobile_number');
        $s_email = $request->get('s_email');

        $b_enabled = $request->get("b_enabled");
        $objects = Vendor::orderBy('id', 'DESC')->with(['get_list_days'])->whereNull('deleted_at');

        if(isset($s_name) && strlen($s_name) > 0)
            $objects = $objects->where('name->' . app()->getLocale(), 'LIKE', '%'.$s_name.'%');
        if(isset($s_mobile_number) && strlen($s_mobile_number) > 0)
            $objects = $objects->where('phone', 'LIKE', '%'.$s_mobile_number.'%');
        if(isset($s_email) && strlen($s_email) > 0)
            $objects = $objects->where('email', 'LIKE', '%'.$s_email.'%');

        if(isset($b_enabled) && $b_enabled == 1)
            $objects = $objects->where('status', $b_enabled);

        if(isset($b_enabled) && $b_enabled == 2)
            $objects = $objects->where('status', '0');

        return DataTables::of($objects)
            ->addColumn('id', function ($object) {
                return $object->id;
            })
            ->addColumn('name', function ($object) {
                return $object->getTranslation('name', app()->getLocale())??'--';
            })
            ->addColumn('email', function ($object) {
                return $object->email;
            })
            ->addColumn('user', function ($object) {
                return $object->user->name;
            })
            ->addColumn('b_enabled', function ($object) {
                $is_enabled = '';
                $is_enabled .= '<div class="col-12">';
                $is_enabled .= '<span class="switch switch-sm switch-outline switch-icon switch-primary"><label>';
                if ($object->status == TRUE) {
                    $is_enabled .= '<input onclick="ch_st(' . $object->id . ',\'' . route('vendor.status') . '\',\'' . csrf_token() . '\')" type="checkbox" checked="checked" name="select" />';
                } else {
                    $is_enabled .= '<input onclick="ch_st(' . $object->id . ',\'' . route('vendor.status') . '\',\'' . csrf_token() . '\')" type="checkbox" name="select" />';
                }
                $is_enabled .= '<span></span></label>';
                $is_enabled .= '</span>';
                $is_enabled .= '</div>';
                return $is_enabled;
            })

            ->addColumn('actions', function ($object) {
                $action = '';
                if(Auth::user()->hasPermission('vendor_update')) {
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
                                    <a href="'.route("vendor.edit", $object->id).'" class="navi-link">
                                        <span class="navi-icon"><i class="la la-list-alt"></i></span>
                                        <span class="navi-text">'.trans("table.action_edit").'</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a href="'.route("vendor.show.file", $object->id).'" class="navi-link">
                                        <span class="navi-icon"><i class="la la-file-photo-o"></i></span>
                                        <span class="navi-text">'.trans("store.file").'</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>';
                }
                if(Auth::user()->hasPermission('vendor_delete')) {
                    $action .= '
                    <a href="javascript:;" onclick="DeleteConfirm('.$object->id.',\''.route("vendor.destroy", $object->id).'\')" class="btn btn-sm btn-default btn-text-primary btn-hover-primary btn-icon" title="Delete">
                    <span class="svg-icon svg-icon-md">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"/>
                                <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"/>
                                <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"/>
                            </g>
                        </svg>
                    </span>
                </a>';
                }
                if(Auth::user()->hasPermission('package_create')) {
                    $action .= '
                    <a href="'.route("packages.newPackage.for.user", $object->id).'" class="btn btn-sm btn-default btn-text-primary btn-hover-primary btn-icon" title="'.__('package.package').'">
                    <span class="svg-icon svg-icon-md"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo1\dist/../src/media/svg/icons\Shopping\Box2.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24"/>
                            <path d="M4,9.67471899 L10.880262,13.6470401 C10.9543486,13.689814 11.0320333,13.7207107 11.1111111,13.740321 L11.1111111,21.4444444 L4.49070127,17.526473 C4.18655139,17.3464765 4,17.0193034 4,16.6658832 L4,9.67471899 Z M20,9.56911707 L20,16.6658832 C20,17.0193034 19.8134486,17.3464765 19.5092987,17.526473 L12.8888889,21.4444444 L12.8888889,13.6728275 C12.9050191,13.6647696 12.9210067,13.6561758 12.9368301,13.6470401 L20,9.56911707 Z" fill="#000000"/>
                            <path d="M4.21611835,7.74669402 C4.30015839,7.64056877 4.40623188,7.55087574 4.5299008,7.48500698 L11.5299008,3.75665466 C11.8237589,3.60013944 12.1762411,3.60013944 12.4700992,3.75665466 L19.4700992,7.48500698 C19.5654307,7.53578262 19.6503066,7.60071528 19.7226939,7.67641889 L12.0479413,12.1074394 C11.9974761,12.1365754 11.9509488,12.1699127 11.9085461,12.2067543 C11.8661433,12.1699127 11.819616,12.1365754 11.7691509,12.1074394 L4.21611835,7.74669402 Z" fill="#000000" opacity="0.3"/>
                        </g>
                    </svg><!--end::Svg Icon--></span>
                </a>';
//                    $action .= $object->get_list_days()->count();
                    if($object->get_list_days()->count() == 1) {
                        if ($object->get_list_days[0]->number_of_order == null) {

                        } else {
                            $action.= '
                        <a href="'.route("packages.rnewPackage.for.user", $object->id).'" class="btn btn-sm btn-default btn-text-primary btn-hover-primary btn-icon" title="'.__('package.rpackage').'">
                                                <span class="svg-icon svg-icon-md"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo1\dist/../src/media/svg/icons\Shopping\Box2.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                    <!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\General\Half-star.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon points="0 0 24 0 24 24 0 24"/>
                                    <path d="M12,4.25932872 C12.1488635,4.25921584 12.3000368,4.29247316 12.4425657,4.36281539 C12.6397783,4.46014562 12.7994058,4.61977315 12.8967361,4.81698575 L14.9389263,8.95491503 L19.5054023,9.61846284 C20.0519472,9.69788046 20.4306287,10.2053233 20.351211,10.7518682 C20.3195865,10.9695052 20.2170993,11.1706476 20.0596157,11.3241562 L16.7552826,14.545085 L17.5353298,19.0931094 C17.6286908,19.6374458 17.263103,20.1544017 16.7187666,20.2477627 C16.5020089,20.2849396 16.2790408,20.2496249 16.0843804,20.1472858 L12,18 L12,4.25932872 Z" fill="#000000" opacity="0.3"/>
                                    <path d="M12,4.25932872 L12,18 L7.91561963,20.1472858 C7.42677504,20.4042866 6.82214789,20.2163401 6.56514708,19.7274955 C6.46280801,19.5328351 6.42749334,19.309867 6.46467018,19.0931094 L7.24471742,14.545085 L3.94038429,11.3241562 C3.54490071,10.938655 3.5368084,10.3055417 3.92230962,9.91005817 C4.07581822,9.75257453 4.27696063,9.65008735 4.49459766,9.61846284 L9.06107374,8.95491503 L11.1032639,4.81698575 C11.277344,4.464261 11.6315987,4.25960807 12,4.25932872 Z" fill="#000000"/>
                                </g>
                            </svg><!--end::Svg Icon-->
                                                </svg><!--end::Svg Icon--></span>
                                            </a>
                        ';
                        }

                    } else  if($object->get_list_days()->exists()){
                        $action.= '
                        <a href="'.route("packages.rnewPackage.for.user", $object->id).'" class="btn btn-sm btn-default btn-text-primary btn-hover-primary btn-icon" title="'.__('package.rpackage').'">
                                                <span class="svg-icon svg-icon-md"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo1\dist/../src/media/svg/icons\Shopping\Box2.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                    <!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\General\Half-star.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon points="0 0 24 0 24 24 0 24"/>
                                    <path d="M12,4.25932872 C12.1488635,4.25921584 12.3000368,4.29247316 12.4425657,4.36281539 C12.6397783,4.46014562 12.7994058,4.61977315 12.8967361,4.81698575 L14.9389263,8.95491503 L19.5054023,9.61846284 C20.0519472,9.69788046 20.4306287,10.2053233 20.351211,10.7518682 C20.3195865,10.9695052 20.2170993,11.1706476 20.0596157,11.3241562 L16.7552826,14.545085 L17.5353298,19.0931094 C17.6286908,19.6374458 17.263103,20.1544017 16.7187666,20.2477627 C16.5020089,20.2849396 16.2790408,20.2496249 16.0843804,20.1472858 L12,18 L12,4.25932872 Z" fill="#000000" opacity="0.3"/>
                                    <path d="M12,4.25932872 L12,18 L7.91561963,20.1472858 C7.42677504,20.4042866 6.82214789,20.2163401 6.56514708,19.7274955 C6.46280801,19.5328351 6.42749334,19.309867 6.46467018,19.0931094 L7.24471742,14.545085 L3.94038429,11.3241562 C3.54490071,10.938655 3.5368084,10.3055417 3.92230962,9.91005817 C4.07581822,9.75257453 4.27696063,9.65008735 4.49459766,9.61846284 L9.06107374,8.95491503 L11.1032639,4.81698575 C11.277344,4.464261 11.6315987,4.25960807 12,4.25932872 Z" fill="#000000"/>
                                </g>
                            </svg><!--end::Svg Icon-->
                                                </svg><!--end::Svg Icon--></span>
                                            </a>
                        ';
                    }

                }
                $action .= '
                    <a href="'.route("vendor.city", $object->id).'" class="btn btn-sm btn-default btn-text-primary btn-hover-primary btn-icon" title="'.__('store.city').'">
                    <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Map\Marker1.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <path d="M5,10.5 C5,6 8,3 12.5,3 C17,3 20,6.75 20,10.5 C20,12.8325623 17.8236613,16.03566 13.470984,20.1092932 C12.9154018,20.6292577 12.0585054,20.6508331 11.4774555,20.1594925 C7.15915182,16.5078313 5,13.2880005 5,10.5 Z M12.5,12 C13.8807119,12 15,10.8807119 15,9.5 C15,8.11928813 13.8807119,7 12.5,7 C11.1192881,7 10,8.11928813 10,9.5 C10,10.8807119 11.1192881,12 12.5,12 Z" fill="#000000" fill-rule="nonzero"/>
    </g>
</svg><!--end::Svg Icon--></span>
                </a>';
                return $action;
            })
            ->addColumn('packages', function ($object) {
//                if($object->get_list_days()->exists())
//                {
//                    if($object->get_list_days->number_of_order == null) {
//                        $startDay = Carbon::parse($object->get_list_days->date_from);
//                        $endDay = Carbon::parse($object->get_list_days->date_to);
//                        return $testdate = $startDay->diffInDays($endDay) . ' ' . __('store.orderDay');
//                    } else {
//                        return $object->get_list_days->number_of_order . ' ' . __('order.orderStay');
//                    }
//                } else {
//                    return 2;
//                }
                if($object->get_list_days()->count() == 1) {
                    if ($object->get_list_days[0]->number_of_order == null) {
                        $startDay = Carbon::parse($object->get_list_days[0]->date_from);
                        $endDay = Carbon::parse($object->get_list_days[0]->date_to);
                        return $testdate = $startDay->diffInDays($endDay) . ' ' . __('store.orderDay');
                    } else {
                        return $object->get_list_days[0]->number_of_order . ' ' . __('order.orderStay');
                    }
                } else if($object->get_list_days()->exists()){
                    return __('package.bothPackage');
                } else {
                    return __('package.expired');
                }
            })
            ->addColumn('dt_creation_date', function ($object) {
                return Carbon::parse($object->created_at)->toDateString();
            })
            ->addIndexColumn()
            ->rawColumns(['actions', 'b_enabled'])
            ->make(true);
    }

    /**
     * index page employee
     *
     * @object auth user
     * @return \Illuminate\Http\Response
     */
    public function employeeVendors() {
        return view('panel.employee.index');
    }

    /**
     * datatable yajra
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function employeeDatatable(Request $request)
    {
        //
        $data = $request->all();
        $s_name = $request->get('s_name');
        $s_mobile_number = $request->get('s_mobile_number');
        $s_email = $request->get('s_email');

        $b_enabled = $request->get("b_enabled");
        $objects = Vendor::whereNull('deleted_at')->where('employee_id', Auth::id())->orderBy('id', 'DESC')->with(['get_list_days']);

        if(isset($s_name) && strlen($s_name) > 0)
            $objects = $objects->where('name->' . app()->getLocale(), 'LIKE', '%'.$s_name.'%');
        if(isset($s_mobile_number) && strlen($s_mobile_number) > 0)
            $objects = $objects->where('phone', 'LIKE', '%'.$s_mobile_number.'%');
        if(isset($s_email) && strlen($s_email) > 0)
            $objects = $objects->where('email', 'LIKE', '%'.$s_email.'%');

        if(isset($b_enabled) && $b_enabled == 1)
            $objects = $objects->where('status', $b_enabled);

        if(isset($b_enabled) && $b_enabled == 2)
            $objects = $objects->where('status', '0');

        return DataTables::of($objects)
            ->addColumn('id', function ($object) {
                return $object->id;
            })
            ->addColumn('name', function ($object) {
                return $object->getTranslation('name', app()->getLocale())??'--';
            })
            ->addColumn('email', function ($object) {
                return $object->email;
            })
            ->addColumn('user', function ($object) {
                return $object->user->name;
            })
            ->addColumn('b_enabled', function ($object) {
                $is_enabled = '';
                $is_enabled .= '<div class="col-12">';
                $is_enabled .= '<span class="switch switch-sm switch-outline switch-icon switch-primary"><label>';
                if ($object->status == TRUE) {
                    $is_enabled .= '<input onclick="ch_st(' . $object->id . ',\'' . route('vendor.status') . '\',\'' . csrf_token() . '\')" type="checkbox" checked="checked" name="select" />';
                } else {
                    $is_enabled .= '<input onclick="ch_st(' . $object->id . ',\'' . route('vendor.status') . '\',\'' . csrf_token() . '\')" type="checkbox" name="select" />';
                }
                $is_enabled .= '<span></span></label>';
                $is_enabled .= '</span>';
                $is_enabled .= '</div>';
                return $is_enabled;
            })

            ->addColumn('actions', function ($object) {
                $action = '';
                if(Auth::user()->hasPermission('vendor_update')) {
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
                                    <a href="'.route("vendor.edit", $object->id).'" class="navi-link">
                                        <span class="navi-icon"><i class="la la-list-alt"></i></span>
                                        <span class="navi-text">'.trans("table.action_edit").'</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a href="'.route("vendor.show.file", $object->id).'" class="navi-link">
                                        <span class="navi-icon"><i class="la la-file-photo-o"></i></span>
                                        <span class="navi-text">'.trans("store.file").'</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>';
                }
                if(Auth::user()->hasPermission('vendor_delete')) {
                    $action .= '
                    <a href="javascript:;" onclick="DeleteConfirm('.$object->id.',\''.route("vendor.destroy", $object->id).'\')" class="btn btn-sm btn-default btn-text-primary btn-hover-primary btn-icon" title="Delete">
                    <span class="svg-icon svg-icon-md">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"/>
                                <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"/>
                                <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"/>
                            </g>
                        </svg>
                    </span>
                </a>';
                }
                if(Auth::user()->hasPermission('package_create')) {
                    $action .= '
                    <a href="'.route("packages.newPackage.for.user", $object->id).'" class="btn btn-sm btn-default btn-text-primary btn-hover-primary btn-icon" title="'.__('package.package').'">
                    <span class="svg-icon svg-icon-md"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo1\dist/../src/media/svg/icons\Shopping\Box2.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24"/>
                            <path d="M4,9.67471899 L10.880262,13.6470401 C10.9543486,13.689814 11.0320333,13.7207107 11.1111111,13.740321 L11.1111111,21.4444444 L4.49070127,17.526473 C4.18655139,17.3464765 4,17.0193034 4,16.6658832 L4,9.67471899 Z M20,9.56911707 L20,16.6658832 C20,17.0193034 19.8134486,17.3464765 19.5092987,17.526473 L12.8888889,21.4444444 L12.8888889,13.6728275 C12.9050191,13.6647696 12.9210067,13.6561758 12.9368301,13.6470401 L20,9.56911707 Z" fill="#000000"/>
                            <path d="M4.21611835,7.74669402 C4.30015839,7.64056877 4.40623188,7.55087574 4.5299008,7.48500698 L11.5299008,3.75665466 C11.8237589,3.60013944 12.1762411,3.60013944 12.4700992,3.75665466 L19.4700992,7.48500698 C19.5654307,7.53578262 19.6503066,7.60071528 19.7226939,7.67641889 L12.0479413,12.1074394 C11.9974761,12.1365754 11.9509488,12.1699127 11.9085461,12.2067543 C11.8661433,12.1699127 11.819616,12.1365754 11.7691509,12.1074394 L4.21611835,7.74669402 Z" fill="#000000" opacity="0.3"/>
                        </g>
                    </svg><!--end::Svg Icon--></span>
                </a>';
                }
                $action .= '
                    <a href="'.route("vendor.city", $object->id).'" class="btn btn-sm btn-default btn-text-primary btn-hover-primary btn-icon" title="'.__('store.city').'">
                    <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Map\Marker1.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <path d="M5,10.5 C5,6 8,3 12.5,3 C17,3 20,6.75 20,10.5 C20,12.8325623 17.8236613,16.03566 13.470984,20.1092932 C12.9154018,20.6292577 12.0585054,20.6508331 11.4774555,20.1594925 C7.15915182,16.5078313 5,13.2880005 5,10.5 Z M12.5,12 C13.8807119,12 15,10.8807119 15,9.5 C15,8.11928813 13.8807119,7 12.5,7 C11.1192881,7 10,8.11928813 10,9.5 C10,10.8807119 11.1192881,12 12.5,12 Z" fill="#000000" fill-rule="nonzero"/>
    </g>
</svg><!--end::Svg Icon--></span>
                </a>';
                return $action;
            })
            ->addColumn('packages', function ($object) {
                if($object->get_list_days()->exists())
                {
                    if($object->get_list_days->number_of_order == null) {
                        $startDay = Carbon::parse($object->get_list_days->date_from);
                        $endDay = Carbon::parse($object->get_list_days->date_to);
                        return $testdate = $startDay->diffInDays($endDay) . ' ' . __('store.orderDay');
                    } else {
                        return $object->get_list_days->number_of_order . ' ' . __('order.orderStay');
                    }
                } else {
                    return 2;
                }
            })
            ->addColumn('dt_creation_date', function ($object) {
                return Carbon::parse($object->created_at)->toDateString();
            })
            ->addIndexColumn()
            ->rawColumns(['actions', 'b_enabled'])
            ->make(true);
    }

    /**
     * get employee from user table
     *
     * @return \Illuminate\Http\Response
     */
    public function employeeActive() {
        $employee = User::whereRoleIs(['employee'])->get();
        return response()->json(['employee' => $employee]);
    }
    
    public function mapAdmin() {
        if(Auth::user()->hasRole('superadministrator') || Auth::user()->hasRole('administrator')) {
            $drivers = Delivery::where('status', '1')
            // ->has('acitve_driver')
            // ->whereHas('users', function($q) {
            //     $q->where('is_online', 1);
            // })
            ->with('acitve_driver','active_order')
                ->whereHas('acitve_driver')
            // ->orWhereHas('active_order')
            ->get();
            // return $drivers;
            $drivers_collection = Deliveryresource::collection($drivers);
            return response()->json(['delivery' => $drivers_collection]);
        } else if(Auth::user()->hasRole('vendor')){
            $drivers = Drivers::where('user_id', auth()->user()->id)
            ->with('user')
            ->whereHas('user', function ($q) {
                $q->where('is_online', '1');
            })
            ->get();
            // return $drivers;
            $drivers_collection = DeliveryVendorresource::collection($drivers);
            return response()->json(['delivery' => $drivers_collection]);
        }
    }
    
    public function mapVendorTracking() {
        if(Auth::user()->hasRole('superadministrator') || Auth::user()->hasRole('administrator')) {
            $drivers = Delivery::where('status', '1')
            // ->has('acitve_driver')
            // ->whereHas('users', function($q) {
            //     $q->where('is_online', 1);
            // })
            ->with('acitve_driver','active_order')
                ->whereHas('acitve_driver')
            // ->orWhereHas('active_order')
            ->get();
            // return $drivers;
            $drivers_collection = Deliveryresource::collection($drivers);
            return response()->json(['delivery' => $drivers_collection]);
        } else if(Auth::user()->hasRole('vendor')){
           $drivers = Drivers::query()
            ->where('user_id', auth()->id())
            ->with('user')
            ->whereHas('user', function($q) {
                $q->where('is_online', '1');
            })
            ->get();
            $drivers_collection = DeliveryVendorresource::collection($drivers);
            return response()->json(['delivery' => $drivers_collection]);
        }
    }
}
