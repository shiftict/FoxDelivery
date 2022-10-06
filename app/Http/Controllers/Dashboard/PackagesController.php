<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Packages\PackagesResource;
use App\Models\DeliveryTime;
use App\Models\Packages;
use App\Models\Vendor;
use App\Models\VendorListPackages;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class PackagesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:package_read'])->only('index');
        $this->middleware(['permission:package_create'])->only('create');
        $this->middleware(['permission:package_delete'])->only('destroy');
        $this->middleware(['permission:package_update'])->only('edit');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('panel.package.index');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request)
    {
        //
        $update = Packages::find($request->pk_i_id);
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
     * Show the form for creating a new resource. (add new package for user)
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $packageList = VendorListPackages::where('vendor_id', $id)->where('status', '1')->get();
        if($packageList->count() > 0)
        {
            return view('panel.package.editPackages', compact('id', 'packageList'));
        }
        //
        return view('panel.package.create', compact('id'));
    }

    /**
     * Show the form for creating a new resource. ( add new packeage )
     *
     * @return \Illuminate\Http\Response
     */
    public function createPackeage()
    {
        //
        return view('panel.package.createSubscription');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $packageList = $request->vendore;
//        return $packageList;
//        if(!$packageList)
//            return redirect()->back();
        $user_id = Vendor::where('id', $request->vendore)->first();
        if(!$user_id) {
            return response(['status'=>false]);
        }
//        return $user_id;
        if($request->package == 1) {
//            return 1;
            $validator = $this->validate($request, [
                'packageHours'                      => 'required|array',
                'packageHours.*.start_shift'        => 'required',
                'packageHours.*.end_shift'          => 'required',
                'mainPackageHours.start'            => 'required',
                'mainPackageHours.end'              => 'after_or_equal:starts|nullable',
                'packageHours.option.*.drivers'     => 'required|exists:users,id',
                'mainPackageHours.pricing'          => 'required|max:255',
            ]);
            // DB::beginTransaction();
            // only package per hours
            $packageOrderFrom = $request['mainPackageHours']['start'];
            $packageOrderTo = $request['mainPackageHours']['end'];
            $packageOrderPrice = $request['mainPackageHours']['pricing'];
            $packageOrderVendor = $packageList;
            VendorListPackages::where('vendor_id', $request->vendore)->update(['status' => '0']);
            // append new array for data list packages
            $dataPackagePerOrder = ['date_from' => $packageOrderFrom,
                'date_to' => $packageOrderTo,
                'vendor_id' => $request->vendore,
                'status' => '1',
                'user_id' => $user_id->user_id,
                'pricing' => $packageOrderPrice];
            // initialize new vendor list packages
            $VendorListPackages = VendorListPackages::create($dataPackagePerOrder);
//            return $VendorListPackages;
            // initialize time delivery
            // foreach from array
            DeliveryTime::where('vendor_id', $request->vendore)->update(['status' => '0']);
            foreach($request->packageHours as $key => $value) {
                // check data its not empty
                if($value['start_shift'] != null && $value['start_shift'] != null && $value['start_shift'] != null) {
                    // filter data from requests
                    $start_shift = Carbon::parse($value['start_shift']);
                    $end_shift = Carbon::parse($value['end_shift']);
                    $drivers = $value['drivers'];
                    // format data for delivery
                    $formatData = ['time_from' => $start_shift,
                        'time_to' => $end_shift,
                        'vendor_id' => $request->vendore,
                        'status' => '1',
                        'vendor_list_package_id' => $VendorListPackages->id,
                        'delivery_id' => $drivers];
                    // initialize time delivery
                    $createTimeDelivry = DeliveryTime::create($formatData);
                }
            }
            // DB::commit();
            return 0;
        } else {
            $validator = $this->validate($request, [
                'packageOrder'                 => 'required|array',
                'packageOrder.pricing'         => 'required|max:255',
                'packageOrder.stock'           => 'required|max:255',
                'packageOrder.startL_order'    => 'required',
               'packageOrder.endL_order'       => 'required_if:packageOrder.startL_order,!=,null|after_or_equal:startL_order',
                'packageOrder.endL_order'      => 'after_or_equal:startL_order|nullable',
            ]);
            // DB::beginTransaction();
            // only package per orders
            $packageOrder = $request->packageOrder;
            $packageOrderVendor = $packageList;
            // date format
            $startL_order = Carbon::parse($packageOrder['startL_order'])->addHour();
            $endL_order = Carbon::parse($packageOrder['endL_order'])->addHour();
            // append format data
            $pricing = $packageOrder['pricing'];
            $stock = $packageOrder['stock'];
            // append new array for data list packages
            $dataPackagePerOrder = ['date_from' => $startL_order,
                'date_to' => $endL_order,
                'vendor_id' => $packageList,
                'user_id' => $user_id->user_id,
                'pricing' => $pricing,
                'status' => '1',
                'number_of_order' => $stock];
            // initialize new vendor list packages
            VendorListPackages::where('vendor_id', $packageList)->update(['status' => '0']);
            DeliveryTime::where('vendor_id', $packageList)->update(['status' => '0']);
            $VendorListPackages = VendorListPackages::create($dataPackagePerOrder);
            // DB::commit();
            return $VendorListPackages;
            return 0;
        }
    }

    /**
     * Store a newly created resource in storage. ( add new packeage )
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storePackeage(Request $request)
    {
        //
        if($request->package == 1) {

            return response()->json(['status' => 1]);
        } elseif ($request->package == 0) {
            $validator = $this->validate($request, [
                'packageOrder'                 => 'required|array',
                'packageOrder.pricing'         => 'required|min:1',
                'packageOrder.stock'           => 'required|min:1',
                'packageOrder.startL_order'    => 'required',
                'packageOrder.endL_order'       => 'required_if:packageOrder.startL_order,!=,null|after_or_equal:startL_order',
                'packageOrder.endL_order'      => 'after_or_equal:startL_order|nullable',
            ]);
            $data = ['price' => $request->packageOrder['pricing'],
                'number_of_orders' => $request->packageOrder['stock'],
                'status' => '1',
                'type' => $request->package,
                'expired_at' => $request->packageOrder['endL_order'],
                'start_at' => $request->packageOrder['startL_order']
            ];
            Packages::query()->create($data);
            return response()->json(['status' => 1]);
            return redirect()->route('packages.index');
        } else {
            return response()->json(['status' => 0]);
        }
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
        $packages = VendorListPackages::where(['vendor_id' => $id, 'status' => '1'])->with(['driver'])->get();
        return PackagesResource::collection($packages);
    }

    // update package active
    public function updateActivePackage(Request $request) {
        $packages = VendorListPackages::where(['vendor_id' => $request->vendore, 'status' => '1'])->with(['driver'])->get();
        if(!$packages) {
            return 1;
        }
        if($request->type_package == 1 && $packages->count() == 1) {
            $validator = $this->validate($request, [
                // peer hours
                'packageHours'    => 'required|array',
                'mainPackageHours.start'           => 'required',
                'mainPackageHours.end'             => 'required_if:mainPackageHours.start,!=,null|after_or_equal:start',
                'mainPackageHours.end'             => 'after_or_equal:starts|nullable',
                'mainPackageHours.pricing'        => 'required|min:1',
            ]);
            // peer hours
            // check type package by id key from collection
            $keysArrayOrder = '';
            $keysArrayHours = '';
            $collection = collect($packages);
            $collection->contains(function($key, $value) use (&$keysArrayOrder, &$keysArrayHours){
                if($key->number_of_order == null) {
                    return $keysArrayHours = $value;
                }
                if($key->number_of_order !== null) {
                    return $keysArrayOrder = $value;
                }
            });
            // id package peer hours
            $idPeerHours = $packages[$keysArrayHours]->id;
            // data peer hours
            $end_hours = Carbon::parse($request->mainPackageHours['end'])->addHour();
            $start_hours = Carbon::parse($request->mainPackageHours['start'])->addHour();
            $pricing_hours = $request->mainPackageHours['pricing'];
            $drivers_hours = $request->mainPackageHours['drivers'];
            $dataPeerOrder = [
                'date_from'=> $start_hours,
                'date_to'=> $end_hours,
                'pricing'=> $pricing_hours,
                'driver_number'=> $drivers_hours,
            ];
            // update data at database
            VendorListPackages::where('id',$idPeerHours)->update($dataPeerOrder);
            DeliveryTime::where('vendor_list_package_id', $idPeerHours)->delete();
            foreach($request->timer_driver as $value) {
                if($value['hours'] != null && $value['vehicle'] != null) {
                    $vehicle = $value['vehicle'];
                    $hours = $value['hours'];
                    // format data for delivery
                    $formatData = ['hours' => $hours,
                        'vehicle' => $vehicle,
                        'vendor_id' => $request->vendore,
                        'vendor_list_package_id' => $idPeerHours];
                    // initialize time delivery
                    $createTimeDelivry = DeliveryTime::create($formatData);
                }
            }
        } else if($request->type_package == 0 && $packages->count() == 1) {
            // peer order
            $validator = $this->validate($request, [
                'packageOrder'                 => 'required|array',
                'packageOrder.pricing'         => 'required|min:1',
                'packageOrder.stock'           => 'required|min:1',
                'packageOrder.start'    => 'required',
                'packageOrder.end'       => 'required_if:packageOrder.start,!=,null|after_or_equal:start',
                'packageOrder.end'      => 'after_or_equal:start|nullable',
            ]);
            // check type package by id key from collection
            $keysArrayOrder = '';
            $keysArrayHours = '';
            $collection = collect($packages);
            $collection->contains(function($key, $value) use (&$keysArrayOrder, &$keysArrayHours){
                if($key->number_of_order == null) {
                    return $keysArrayHours = $value;
                }
                if($key->number_of_order !== null) {
                    return $keysArrayOrder = $value;
                }
            });
            // id package peer order
            $idPeerOrder = $packages[$keysArrayOrder]->id;

            // data peer order
            $end = Carbon::parse($request->packageOrder['end'])->addHour();
            $start = Carbon::parse($request->packageOrder['start'])->addHour();
            $pricing = $request->packageOrder['pricing'];
            $stock = $request->packageOrder['stock'];
            $dataPeerOrder = [
                'date_from'=> $start,
                'date_to'=> $end,
                'pricing'=> $pricing,
                'number_of_order'=> $stock,
            ];
            // update data at database
            VendorListPackages::where('id',$idPeerOrder)->update($dataPeerOrder);
        } else if($request->type_package == 2) {
            $validator = $this->validate($request, [
                // peer hours
                'packageHours'    => 'required|array',
                'mainPackageHours.start'           => 'required',
                'mainPackageHours.end'             => 'required_if:mainPackageHours.start,!=,null|after_or_equal:start',
                'mainPackageHours.end'             => 'after_or_equal:starts|required',
                'mainPackageHours.pricing'        => 'required|min:1',
                'mainPackageHours.drivers'        => 'required',
                // peer order
                'packageOrder'                 => 'required|array',
                'packageOrder.pricing'         => 'required|min:1',
                'packageOrder.stock'           => 'required|min:1',
                'packageOrder.start'    => 'required',
                'packageOrder.end'       => 'required_if:packageOrder.start,!=,null|after_or_equal:start',
                'packageOrder.end'      => 'after_or_equal:start|required',
            ]);
            // both package
            // check type package by id key from collection
            $keysArrayOrder = '';
            $keysArrayHours = '';
            $collection = collect($packages);
            $collection->contains(function($key, $value) use (&$keysArrayOrder, &$keysArrayHours){
                if($key->number_of_order == null) {
                    return $keysArrayHours = $value;
                }
                if($key->number_of_order !== null) {
                    return $keysArrayOrder = $value;
                }
            });
            $arr = [1, 0];
            $user = Vendor::find($request->vendore);
            // data peer order
            $end = Carbon::parse($request->packageOrder['end'])->addHour();
            $start = Carbon::parse($request->packageOrder['start'])->addHour();
            $pricing = $request->packageOrder['pricing'];
            $stock = $request->packageOrder['stock'];
            $dataPeerOrder = [
                'date_from'=> $start,
                'date_to'=> $end,
                'pricing'=> $pricing,
                'number_of_order'=> $stock,
                'vendor_id'=> $request->vendore,
                'user_id'=> $user->user_id,
            ];
            // data peer hours
            $end_hours = Carbon::parse($request->mainPackageHours['end'])->addHour();
            $start_hours = Carbon::parse($request->mainPackageHours['start'])->addHour();
            $pricing_hours = $request->mainPackageHours['pricing'];
            $drivers_hours = $request->mainPackageHours['drivers'];
            $dataPeerHours = [
                'date_from'=> $start_hours,
                'date_to'=> $end_hours,
                'pricing'=> $pricing_hours,
                'driver_number'=> $drivers_hours,
                'vendor_id'=> $request->vendore,
                'user_id'=> $user->user_id,
            ];

            if(count($packages) > 1) {
                // return 1;
                // id package peer hours
                if(in_array($keysArrayHours, $arr)) {
                    // return 12;
                    $idPeerHours = $packages[$keysArrayHours]->id;
                    // update data at database (peer hours)
                    $peerH = VendorListPackages::where('id',$idPeerHours)->first();
                    $peerH->update($dataPeerHours);
                    DeliveryTime::where('vendor_list_package_id', $idPeerHours)->delete();
                    foreach($request->timer_driver as $value) {
                        if($value['hours'] != null && $value['vehicle'] != null) {
                            $vehicle = $value['vehicle'];
                            $hours = $value['hours'];
                            // format data for delivery
                            $formatData = ['hours' => $hours,
                                'vehicle' => $vehicle,
                                'vendor_id' => $request->vendore,
                                'vendor_list_package_id' => $idPeerHours];
                            // initialize time delivery
                            $createTimeDelivry = DeliveryTime::create($formatData);
                        }
                    }
                    VendorListPackages::where('number_of_order', '<>', null)->where('status', '1')->update(['status' => '0']);
                    // return VendorListPackages::where('number_of_order', '<>', null)->where('status', '1')->get();
                    VendorListPackages::create($dataPeerOrder);
                }
                
                // id package peer order
                if(in_array($keysArrayOrder, $arr)) {
                    $idPeerOrder = $packages[$keysArrayOrder]->id;
                    // update data at database (peer order)
                    //VendorListPackages::where('number_of_order', '<>', null)->update(['status' => '1']);
                    VendorListPackages::where('number_of_order', '<>', null)->where('status', '1')->update(['status' => '0']);
                    $idNew = VendorListPackages::create($dataPeerOrder);
                    DeliveryTime::where('vendor_list_package_id', $idPeerHours)->delete();
                    foreach($request->timer_driver as $value) {
                        if($value['hours'] != null && $value['vehicle'] != null) {
                            $vehicle = $value['vehicle'];
                            $hours = $value['hours'];
                            // format data for delivery
                            $formatData = ['hours' => $hours,
                                'vehicle' => $vehicle,
                                'vendor_id' => $request->vendore,
                                'vendor_list_package_id' => $idNew->id];
                            // initialize time delivery
                            $createTimeDelivry = DeliveryTime::create($formatData);
                        }
                    }
                }
                
                
                return 0;
            } else if(in_array($keysArrayHours, $arr) && $packages[0]->number_of_order == null){
                // return 2;
//                return Carbon::parse($request->packageOrder['end'])->addHour();
//                return Carbon::parse($request->mainPackageHours['end'])->addHour();
                $peerH = VendorListPackages::where('id',$packages[0]->id)->first();
                $peerH->update($dataPeerHours);
                // create data at database (peer hours)
                DeliveryTime::where('vendor_list_package_id', $peerH->id)->delete();
                VendorListPackages::create($dataPeerOrder);
                foreach($request->timer_driver as $value) {
                    if($value['hours'] != null && $value['vehicle'] != null) {
                        $vehicle = $value['vehicle'];
                        $hours = $value['hours'];
                        // format data for delivery
                        $formatData = ['hours' => $hours,
                            'vehicle' => $vehicle,
                            'vendor_id' => $request->vendore,
                            'vendor_list_package_id' => $peerH->id];
                        // initialize time delivery
                        $createTimeDelivry = DeliveryTime::create($formatData);
                    }
                }
                return 222;
            } else if(in_array($keysArrayOrder, $arr) && $packages[0]->number_of_order != null){
                // return 3;
                $peerH = VendorListPackages::where('id',$packages[0]->id)->first();
                $peerO = VendorListPackages::create($dataPeerHours);
                DeliveryTime::where('vendor_list_package_id', $peerH->id)->delete();
                $peerH->update($dataPeerOrder);
                foreach($request->timer_driver as $value) {
                    if($value['hours'] != null && $value['vehicle'] != null) {
                        $vehicle = $value['vehicle'];
                        $hours = $value['hours'];
                        // format data for delivery
                        $formatData = ['hours' => $hours,
                            'vehicle' => $vehicle,
                            'vendor_id' => $request->vendore,
                            'vendor_list_package_id' => $peerO->id];
                        // initialize time delivery
                        $createTimeDelivry = DeliveryTime::create($formatData);
                    }
                }
                return 33333;
            }
            return 5555;
        }
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
        $pa = Packages::find($id);
        if($pa->type == 1) {
            // peer hours
            return view('panel.package.editSubscription', compact('id'));
        } else if($pa->type == 0) {
            // peer order
            return view('panel.package.editSubscription', compact('id', 'pa'));
        } else {
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage. (peer order)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePeerOrder(Request $request, $id)
    {
        //
        $validator = $this->validate($request, [
            'pricing'                 => 'required|min:1',
            'stock'         => 'required|min:1',
            'end_package'           => 'required_if:start_package,!=,null|after_or_equal:start_package',
            'start_package'    => 'required',
        ]);
        $pa = Packages::find($id);
        if($pa) {
            // peer order
            $data = ['price' => $request->pricing,
                'number_of_orders' => $request->stock,
                'expired_at' => $request->end_package,
                'start_at' => $request->start_package
            ];
            $pa->update($data);
            return redirect()->route('packages.index');
        } else {
            return redirect()->back();
        }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $update = Packages::find($request->id);
        if ($update) {
            $update->delete();
            return response()->json([
                'success' => TRUE,
                'message' => __('alert.successUpdate')
            ]);
        }
    }

    /**
     * datatable for packages
     */
    public function datatable(Request $request)
    {
        //
        $data = $request->all();
//        $s_name = $request->get('s_name');
        $s_mobile_number = $request->get('s_mobile_number');
        $s_email = $request->get('s_email');
        $b_hourse = $request->get("s_name");
        $b_enabled = $request->get("b_enabled");

        $objects = Packages::whereNull('deleted_at');

//        if(isset($s_name) && strlen($s_name) > 0)
//            $objects = $objects->where('name->' . app()->getLocale(), 'LIKE', $s_name.'%');

        if(isset($b_enabled))
            $objects = $objects->where('status', $b_enabled);
        if(isset($b_hourse))
            $objects = $objects->where('type', $b_hourse);

        return DataTables::of($objects)
            ->addColumn('id', function ($object) {
//                return app()->getLocale();
                return $object->id;
            })
            ->addColumn('number_of_orders', function ($object) {
                if(strlen($object->number_of_orders) > 0) {
                    return $object->number_of_orders;
                } else {
                    return __('store.packagePerHours');
                }
//                return $object->getTranslation('name', app()->getLocale())??'--';
            })
            ->addColumn('expire_date', function ($object) {
                return Carbon::parse($object->start_at)->toDateString() . ' - ' . Carbon::parse($object->expired_at)->toDateString();
//                return $object->getTranslation('name', app()->getLocale())??'--';
            })
            ->addColumn('price', function ($object) {
                return $object->price;
//                return $object->getTranslation('name', app()->getLocale())??'--';
            })
            ->addColumn('type', function ($object) {
                if($object->type == 0) {
                    return __('store.packagePerOrders');
                } else {
                    return __('store.packagePerHours');
                }
//                return $object->getTranslation('name', app()->getLocale())??'--';
            })
            ->addColumn('b_enabled', function ($object) {
                $is_enabled = '';
                $is_enabled .= '<div class="col-12">';
                $is_enabled .= '<span class="switch switch-sm switch-outline switch-icon switch-primary"><label>';
                if ($object->status == TRUE) {
                    $is_enabled .= '<input onclick="ch_st(' . $object->id . ',\'' . route('packages.status') . '\',\'' . csrf_token() . '\')" type="checkbox" checked="checked" name="select" />';
                } else {
                    $is_enabled .= '<input onclick="ch_st(' . $object->id . ',\'' . route('packages.status') . '\',\'' . csrf_token() . '\')" type="checkbox" name="select" />';
                }
                $is_enabled .= '<span></span></label>';
                $is_enabled .= '</span>';
                $is_enabled .= '</div>';
                return $is_enabled;
            })

            ->addColumn('actions', function ($object) {
                $action = '';
                $action .= '<a href='.route("packages.edit", $object->id).' class="btn btn-sm btn-default btn-text-primary btn-hover-primary btn-icon" title="Delete">
                            <span class="svg-icon svg-icon-md">
                                <!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Design\Edit.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "/>
                                        <rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"/>
                                    </g>
                                </svg><!--end::Svg Icon-->
                            </span>
                        </a>';
                    $action .= '
                            <a href="javascript:;" onclick="DeleteConfirm('.$object->id.',\''.route("packages.destroy", $object->id).'\')" class="btn btn-sm btn-default btn-text-primary btn-hover-primary btn-icon" title="Delete">
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
                return $action;
            })
            ->addColumn('dt_creation_date', function ($object) {
                return Carbon::parse($object->created_at)->toDateString();
            })
            ->addIndexColumn()
            ->rawColumns(['actions', 'b_enabled'])
            ->make(true);
    }

    /**
     * renewal for packages befor expire
     */
    public function renewal(Request $request, $id) {
        $packageList = VendorListPackages::where('vendor_id', $id)->where('status', '1')->get();
        if($packageList->count() == 1)
        {
            if($packageList[0]->number_of_order == null) {
                return redirect()->route('vendor.index');
            }
            return view('panel.package.renewal', compact('id', 'packageList'));
        }
        return view('panel.package.renewal', compact('id', 'packageList'));
    }

    /**
     * renewal for packages befor expire
     */
    public function renewalPost(Request $request) {
        $validator = $this->validate($request, [
            // peer order
            'pricing'         => 'required|min:1',
            'id_user'         => 'required|exists:vendors,id',
            'stok'         => 'required|min:1',
            'start'           => 'required|min:1',
            'end'    => 'required|after_or_equal:start',
        ]);
        $vendor = Vendor::where('id', $request->id_user)->where('status', '1')->first();
        $packageList = VendorListPackages::where('vendor_id', $request->id_user)
            ->where('status', '1')
            ->where('number_of_order', '<>', null)
            ->first();
        if(!$packageList || !$vendor) {
            return redirect()->route('vendor.index');
        }
        if($request->blance) {
            $number_blance = $packageList->number_of_order;
            $data = [
                'vendor_id' => $vendor->id,
                'user_id' => $vendor->user_id,
                'pricing' => $request->pricing,
                'number_of_order' => $request->stok + $number_blance,
                'status' => '1',
                'date_to' => Carbon::parse($request->end)->addHour(),
//                'date_to' => DateTime::createFromFormat('d-m-Y H:i:s', $request->end),
                'date_from' => Carbon::parse($request->start)->addHour(),
//                'date_from' => DateTime::createFromFormat('d-m-Y H:i:s', $request->start)
            ];
            $new_object = VendorListPackages::query()
                ->create($data);
            $packageList->update(['status' => '0']);
            return redirect()->route('vendor.index');
        }
        $data = [
            'vendor_id' => $vendor->id,
            'user_id' => $vendor->user_id,
            'pricing' => $request->pricing,
            'number_of_order' => $request->stok,
            'status' => '1',
            'date_to' => Carbon::parse($request->end)->addHour(),
            'date_from' => Carbon::parse($request->start)->addHour()
        ];
        $new_object = VendorListPackages::query()
            ->create($data);
        $packageList->update(['status' => '0']);
        return redirect()->route('vendor.index');

    }
}
