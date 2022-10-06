<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrdersRequests;
use App\Http\Resources\Delivery\Driversresource;
use App\Models\Delivery;
use App\Models\Drivers;
use App\Models\NotificationDriver;
use App\Models\Order;
use App\Models\NotificationOrder;
use App\Models\NotificationVendorOrder;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorListPackages;
use App\Models\StatusSystem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Http;
use App\Notifications\DriverNotification;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:order_create'])->only('create');
        $this->middleware(['permission:order_delete|order_read|order_update'])->only('index');
        $this->middleware('permission:order_read')->only('show');
        $this->middleware('permission:order_update')->only('edit');
        $this->middleware('permission:order_delete')->only('destroy');
        $this->middleware('role:superadministrator')->only('status');
        $this->middleware('role:superadministrator')->only('setDriver');
        $this->middleware('role:superadministrator')->only('driver');
        $this->middleware('role:superadministrator')->only('statusOrder');
    }
    /**status
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     
    public function index()
    {
        //
        return view('panel.order.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Auth::user()->store->get_list_days->number_of_orderو
        $user = Auth::user()->hasRole('superadministrator');
        if($user) {
            $driverWithTime = Delivery::get();
            return view('panel.order.createAdmin', compact('driverWithTime'));
        }
        if (Auth::user()->stores->status == '0') {
            toastr()->warning(__('alert.pacakgeExpired'));
            return redirect()->route('order.index');
        }
        if(Auth::user()->packages_new()->exists() && Auth::user()->packages_new()->count() > 0) {
            if(Auth::user()->packages_new()->count() > 1) {
                // get all package active
                $activePackage = Auth::user()->packages_new;
                // init variable for key
                $keysArrayOrder = '';
                $keysArrayHours = '';
                // check package type
                $collection = collect($activePackage);
                $collection->contains(function($key, $value) use (&$keysArrayOrder, &$keysArrayHours){
                    if($key->number_of_order == null) {
                        return $keysArrayHours = $value;
                    }
                    if($key->number_of_order !== null) {
                        return $keysArrayOrder = $value;
                    }
                });

                // number of order package peer order
                $number_of_order = $activePackage[$keysArrayOrder]->number_of_order;

                // return view
                return view('panel.order.create', compact('number_of_order'));
            } else {
                $activePackage = Auth::user()->packages_new->first();
                if($activePackage->number_of_order == null) {
                    return view('panel.order.createPeerHours', compact('activePackage'));
                } else {
                    return view('panel.order.createPeerOrder', compact('activePackage'));
                }
            }
        } else {
            toastr()->warning(__('alert.pacakgeExpired'));
            return redirect()->route('order.index');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $statusOreder = StatusSystem::where(['status' => '1', 'defulte_status' => '1'])->first();
        // create order by admin
//        if(Auth::user()->hasRole('superadministrator')) {
            // return $request->all();
//            return $this->adminOrder($request->all());
//            toastr()->success(__('alert.successCreated'), __('alert.success'));
//            return redirect()->route('order.index');
//        }

        if($request->type_order_package == 1) {
            /*$validator = $this->validate($request, [
                'data.*.lat_from'                      => 'required',
                'data.*.long_from'        => 'required',
                'data.*.lat_to'        => 'required',
                'data.*.long_to'        => 'required',
                'data.*.name'        => 'required',
                'data.*.phone'        => 'required|max:10|min:8|number',
                'data.*.date'        => 'required',
                'data.*.from_address'        => 'required',
                'data.*.to_address'        => 'required',
                'data.*.type_order'        => 'required',
                'data.*.block'        => 'required',
                'data.*.home'        => 'required',
                'data.*.sabil'        => 'required',
                'data.*.street'        => 'required',
                'data.*.package_type'        => 'required',
            ]);*/
            $requests = $request->data;
            foreach($requests as $d) {
                // format data
                $formatData = [
                    'items' => $d['items'],
'payment_method' => $d['payment_method'],
'totale_amount' => $d['totale_amount'],
'city_id' => $d['city_id'],
                    
                    'order_reference' => $d['order_reference'],
                    'lat_from' => $d['latFrom'],
                    'long_from' => $d['longFrom'],
                    'lat_to' => $d['latTo'],
                    'long_to' => $d['longTo'],
                    'name' => $d['name'],
                    'phone' => $d['mobile'],
                    'date_from' => Carbon::parse($d['date_from'])->addHour(),
                    'date_to' => Carbon::parse($d['date_to'])->addHour(),
                    'time_from' => $d['time_from'],
                    'time_to' => $d['time_to'],
                    'from_address' => $d['cityFrom'],
                    'to_address' => $d['cityTo'],
                    'type_order' => $d['type_order'],
                    'description' => $d['about'],
                    'block' => $d['block'],
                    'home' => $d['home'],
                    'sabil' => $d['sabil'],
                    'street' => $d['street'],
                    'package_type' => '1', // only 0 its peer hours
                    'created_by' => Auth::id(),
                    'order_status' => $statusOreder->id,
                    'delivery_id' => $d['driver'],
                ];
                // get data vendor and packages
                $storeId = Auth::user()->store->id;
//                $package = Auth::user()->packages;
                $packageId = VendorListPackages::where('status', '1')
                    ->where('user_id', Auth::id())
                    ->where('number_of_order', null)
                    ->first();
                // append data from query
                $data = array_merge($formatData, ['vendor_id' => $storeId,
                    'vendor_list_package_id' => $packageId->id]);
                // create new order
                $newOrder = Order::create($data);
                NotificationOrder::query()->create([
                    'vendor_id' => Auth::user()->store->id,
                    'user_id' => Auth::id(),
                    'order_id' => $newOrder->id,
                ]);
            }
            return response()->json(['status'=>200, 'message'=>__('alert.successCreated')]);
        } elseif($request->type_order_package == 0)  {
            $requests = $request->data;
            foreach($requests as $d) {
                // return $d['latFrom'];
                // format data
                $formatData = [
                    
                    
                    
                    'items' => $d['items'],
'payment_method' => $d['payment_method'],
'totale_amount' => $d['totale_amount'],
'city_id' => $d['city_id'],
                    'order_reference' => $d['order_reference'],
                    
                    'lat_from' => $d['latFrom'],
                    'long_from' => $d['longFrom'],
                    'lat_to' => $d['latTo'],
                    'long_to' => $d['longTo'],
                    'name' => $d['name'],
                    'phone' => $d['mobile'],
                    'date_from' => Carbon::parse($d['date_from'])->addHour(),
                    'date_to' => Carbon::parse($d['date_to'])->addHour(),
                    'time_from' => $d['time_from'],
                    'time_to' => $d['time_to'],
                    'from_address' => $d['cityFrom'],
                    'to_address' => $d['cityTo'],
                    'type_order' => $d['type_order'],
                    'description' => $d['about'],
                    'block' => $d['block'],
                    'home' => $d['home'],
                    'sabil' => $d['sabil'],
                    'street' => $d['street'],
                    'package_type' => '0', // only 0 its peer order
                    'created_by' => Auth::id(),
                    'order_status' => $statusOreder->id,
                ];
                // return $formatData;
                // get data vendor and packages
                $storeId = Auth::user()->store->id;
                $packageId = VendorListPackages::where('status', '1')
                    ->where('user_id', Auth::id())
                    ->where('number_of_order', '<>', null)
                    ->first();
                // append data from query
                $data = array_merge($formatData, ['vendor_id' => $storeId,
                    'vendor_list_package_id' => $packageId->id]);
                // check packages
                if($packageId->number_of_order <= 0){
                    return response()->json(['status'=>422, 'message'=>__('alert.pacakgeExpired')]);
                }
                // create new order
                $newOrder = Order::create($data);
                $packageId->decrement('number_of_order',1);
                if($packageId->number_of_order <= 0) {
                    $packageId->update(['status' => '0']);
                }
                NotificationOrder::query()->create([
                    'vendor_id' => Auth::user()->store->id,
                    'user_id' => Auth::id(),
                    'order_id' => $newOrder->id,
                ]);
            }
            return response()->json(['status'=>200, 'message'=>__('alert.successCreated')]);
        }
    }

    /**
     * Store a newly created order by admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function adminCreateOrder(Request $request) {
        // return 1;
        $statusOreder = StatusSystem::where(['status' => '1', 'defulte_status' => '1'])->first();
        // get data vendor and packages
        $storeWithRelation = User::where('id', $request->vendor_id)
            ->with(['store', 'package_orders', 'package_hours'])
            ->first();
        $storeId = $storeWithRelation->store->id;
        // return $packageId = $storeWithRelation->package_hours;
        
        
//        return $request->tito;
        if($request->package_type == 0 || $request->tito != 'hours') {
            $packageId = $storeWithRelation->package_orders->id;
            $package_peer_order = VendorListPackages::find($packageId);
            foreach($request->data as $d) {
                // format data
                $formatData = [
                    'items' => $d['items'],
                    'payment_method' => $d['payment_method'],
                    'totale_amount' => $d['totale_amount'],
                    'city_id' => $d['city_id'],
                    'order_reference' => $d['order_reference'],
                    'lat_from' => $d['latFrom'],
                    'long_from' => $d['longFrom'],
                    'lat_to' => $d['latTo'],
                    'long_to' => $d['longTo'],
                    'name' => $d['name'],
                    'phone' => $d['mobile'],
                    'date_from' => Carbon::parse($d['date_from'])->addHour(),
                    'date_to' => Carbon::parse($d['date_to'])->addHour(),
                    'time_from' => $d['time_from'],
                    'time_to' => $d['time_to'],
                    'from_address' => $d['cityFrom'],
                    'to_address' => $d['cityTo'],
                    'type_order' => $d['type_order'],
                    'description' => $d['about'],
                    'block' => $d['block'],
                    'home' => $d['home'],
                    'sabil' => $d['sabil'],
                    'street' => $d['street'],
                    'package_type' => '0', // only 0 its peer order
                    'created_by' => $request->vendor_id,
                    'create_by_admin' => '1',
                    'order_status' => $statusOreder->id,
                    'delivery_id' => $d['driver'],
                    'vendor_list_package_id' => $packageId,
                    'vendor_id' => $storeId,
                ];
                // create new order
                $newOrder = Order::create($formatData);
                $package_peer_order->decrement('number_of_order',1);
                if($package_peer_order->number_of_order <= 0) {
                    $package_peer_order->update(['status' => '0']);
                }
                NotificationOrder::query()->create([
                    'vendor_id' => $storeId,
                    'user_id' => Auth::id(),
                    'order_id' => $newOrder->id,
                ]);
            }
            return response()->json(['status'=>200, 'message'=>__('alert.successCreated')]);
        } else if($request->package_type == 1) {
            $packageId = $storeWithRelation->package_hours->id;
            $package_peer_order = VendorListPackages::find($packageId);
            
            foreach($request->data as $d) {
                // format data
                $formatData = [
                    'items' => $d['items'],
                    'totale_amount' => $d['totale_amount'],
                    'order_reference' => $d['order_reference'],
                    'lat_from' => $d['latFrom'],
                    'long_from' => $d['longFrom'],
                    'lat_to' => $d['latTo'],
                    'long_to' => $d['longTo'],
                    'name' => $d['name'],
                    'phone' => $d['mobile'],
                    'date_from' => Carbon::parse($d['date_from'])->addHour(),
                    'date_to' => Carbon::parse($d['date_to'])->addHour(),
                    'time_from' => $d['time_from'],
                    'time_to' => $d['time_to'],
                    'from_address' => $d['cityFrom'],
                    'to_address' => $d['cityTo'],
                    'type_order' => $d['type_order'],
                    'description' => $d['about'],
                    'block' => $d['block'],
                    'home' => $d['home'],
                    'sabil' => $d['sabil'],
                    'street' => $d['street'],
                    'package_type' => '1', // only 0 its peer hours
                    'created_by' => $request->vendor_id,
                    'create_by_admin' => '1',
                    'order_status' => $statusOreder->id,
                    'delivery_id' => $d['driver'],
                    'vendor_list_package_id' => $packageId,
                    'vendor_id' => $storeId,
                ];
                // create new order
                $newOrder = Order::create($formatData);
//                $package_peer_order->decrement('number_of_order',1);
//                if($package_peer_order->number_of_order <= 0) {
//                    $package_peer_order->update(['status' => '0']);
//                }
                NotificationOrder::query()->create([
                    'vendor_id' => $storeId,
                    'user_id' => Auth::id(),
                    'order_id' => $newOrder->id,
                ]);
                if($d['driver']) {
                    $driver = Delivery::find($d['driver']);
                    NotificationDriver::create([
                        'order_id' => $newOrder->id,
                        'user_id' => Auth::id(),
                        'driver_id' => $d['driver'],
                    ]);
                    $data = ['title' => 'Fox Delivery',
                    'order_id' => $newOrder->id,
                    'body' => 'لديك طلب جديد #' . $newOrder->id];
                    $user = User::find($driver->user_id);
                    // return $user;
                    $user->notify(new DriverNotification($data));
                }
            }
            return response()->json(['status'=>200, 'message'=>__('alert.successCreated')]);
        }
    }

    /**
     * Store a newly created peer order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function peerOrder(Request $request) {
        $statusOreder = StatusSystem::where(['status' => '1', 'defulte_status' => '1'])->first();
        foreach($request->all() as $d) {
            // return $d['latFrom'];
            // format data
            $formatData = [
                'items' => $d['items'],
'payment_method' => $d['payment_method'],
'totale_amount' => $d['totale_amount'],
'city_id' => $d['city_id'],
                
                'order_reference' => $d['order_reference'],
                
                'lat_from' => $d['latFrom'],
                'long_from' => $d['longFrom'],
                'lat_to' => $d['latTo'],
                'long_to' => $d['longTo'],
                'name' => $d['name'],
                'phone' => $d['mobile'],
                'date_from' => Carbon::parse($d['date_from'])->addHour(),
                    'date_to' => Carbon::parse($d['date_to'])->addHour(),
                    'time_from' => $d['time_from'],
                    'time_to' => $d['time_to'],
                'from_address' => $d['cityFrom'],
                'to_address' => $d['cityTo'],
                'type_order' => $d['type_order'],
                'description' => $d['about'],
                'block' => $d['block'],
                'home' => $d['home'],
                'sabil' => $d['sabil'],
                'street' => $d['street'],
                'package_type' => '0', // only 0 its peer order
                'created_by' => Auth::id(),
                'order_status' => $statusOreder->id,
            ];
            // return $formatData;
            // get data vendor and packages
            $storeId = Auth::user()->store->id;
            $package = Auth::user()->packages;
            $packageId = $package->id;
            // append data from query
            $data = array_merge($formatData, ['vendor_id' => $storeId,
                'vendor_list_package_id' => $packageId]);
            // check packages
            if($package->number_of_order <= 0){
                return response()->json(['status'=>422, 'message'=>__('alert.pacakgeExpired')]);
            }
            // create new order
            $newOrder = Order::create($data);
            $package->decrement('number_of_order',1);
            if($package->number_of_order <= 0) {
                $package->update(['status' => '0']);
            }
            NotificationOrder::query()->create([
                'vendor_id' => Auth::user()->store->id,
                'user_id' => Auth::id(),
                'order_id' => $newOrder->id,
            ]);
        }
        return response()->json(['status'=>200, 'message'=>__('alert.successCreated')]);
    }

    /**
     * Store a newly created peer hours.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function peerHours(Request $request) {
        $statusOreder = StatusSystem::where(['status' => '1', 'defulte_status' => '1'])->first();
        foreach($request->all() as $d) {
            // format data
            $formatData = [
                
                
                
                'items' => $d['items'],
'payment_method' => $d['payment_method'],
'totale_amount' => $d['totale_amount'],
'city_id' => $d['city_id'],
                'order_reference' => $d['order_reference'],
                
                
                
                
                
                'lat_from' => $d['latFrom'],
                'long_from' => $d['longFrom'],
                'lat_to' => $d['latTo'],
                'long_to' => $d['longTo'],
                'name' => $d['name'],
                'phone' => $d['mobile'],
                'date_from' => Carbon::parse($d['date_from'])->addHour(),
                    'date_to' => Carbon::parse($d['date_to'])->addHour(),
                    'time_from' => $d['time_from'],
                    'time_to' => $d['time_to'],
                'from_address' => $d['cityFrom'],
                'to_address' => $d['cityTo'],
                'type_order' => $d['type_order'],
                'description' => $d['about'],
                'block' => $d['block'],
                'home' => $d['home'],
                'sabil' => $d['sabil'],
                'street' => $d['street'],
                'package_type' => '1', // only 0 its peer hours
                'created_by' => Auth::id(),
                'order_status' => $statusOreder->id,
                'delivery_id' => $d['driver'],
            ];
            // get data vendor and packages
            $storeId = Auth::user()->store->id;
            $package = Auth::user()->packages;
            $packageId = $package->id;
            // append data from query
            $data = array_merge($formatData, ['vendor_id' => $storeId,
                'vendor_list_package_id' => $packageId]);
            // create new order
            $newOrder = Order::create($data);
            NotificationOrder::query()->create([
                'vendor_id' => Auth::user()->store->id,
                'user_id' => Auth::id(),
                'order_id' => $newOrder->id,
            ]);
            if($d['driver']) {
                $driver = Delivery::find($d['driver']);
                NotificationDriver::create([
                    'order_id' => $newOrder->id,
                    'user_id' => Auth::id(),
                    'driver_id' => $d['driver'],
                ]);
                $data = ['title' => 'Fox Delivery',
                'order_id' => $newOrder->id,
                'body' => 'لديك طلب جديد #' . $newOrder->id];
                $user = User::find($driver->user_id);
                // return $user;
                $user->notify(new DriverNotification($data));
            }
            
        }
        return response()->json(['status'=>200, 'message'=>__('alert.successCreated')]);
    }

    /**
     * Store a newly created by super admin in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function adminOrder($data) {
        // format data
        // dd($data);
        // return 1;
        foreach($data as $key => $d) {
            // if($d['latFrom'] && $d['longFrom'] && $d['latTo'] && $d['longTo'] && $d['name'] && $d['mobile'] && $d['date'] && $d['type_order'] && $d['cityFrom'] && $d['cityTo'] && $d['driver']) {
            $statusOreder = StatusSystem::where(['status' => '1', 'defulte_status' => '1'])->first();
            $formatData = ['lat_from' => $d['latFrom'],
                'long_from' => $d['longFrom'],
                'lat_to' => $d['latTo'],
                'long_to' => $d['longTo'],
                'name' => $d['name'],
                'phone' => $d['mobile'],
                'date' => Carbon::parse($d['date']),
                'type_order' => $d['type_order'],
                'description' => $d['about'],
                'from_address' => $d['cityFrom'],
                'to_address' => $d['cityTo'],
                'created_by' => Auth::id(),
                'delivery_id' => $d['driver'],
                'order_status' => $statusOreder->id
              ];
            // create new order
            $newOrder = Order::create($formatData);
            NotificationOrder::query()->create([
                'user_id' => Auth::id(),
                'order_id' => $newOrder->id,
            ]);
        }
        return 0;
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
        if(Auth::user()->hasRole('superadministrator')) {
            $order = Order::query()->with(['delivery'])->find($id);
        } else {
            $order = Order::query()->where('created_by', Auth::user()->id)->find($id);
        }
        if(!$order)
        {
            toastr()->error(__('alert.errorMessage'), __('alert.cancel'));
            return redirect()->route('order.index');
        }
        $message = '<span class="label label-inline label-light-'.$order->statusOrder->cards_color.' font-weight-bold">'.$order->statusOrder->name.'</span>';
        // if($order->order_status == '0') {
        //     $message = '<span class="label label-inline label-light-primary font-weight-bold">'.__('notificationOrder.pending').'</span>';
        // } elseif($order->order_status == '1') {
        //     $message = '<span class="label label-inline label-light-info font-weight-bold">'.__('notificationOrder.tripHasStarted').'</span>';
        // } elseif($order->order_status == '2') {
        //     $message = '<span class="label label-inline label-light-warning font-weight-bold">'.__('notificationOrder.received').'</span>';
        // } elseif($order->order_status == '3') {
        //     $message = '<span class="label label-inline label-light-success font-weight-bold">'.__('notificationOrder.sentDeliveredHanded').'</span>';
        // } elseif($order->order_status == '4') {
        //     $message = '<span class="label label-inline label-light-danger font-weight-bold">'.__('notificationOrder.cancellation').'</span>';
        // }
        if(Auth::user()->store) {
            if(Auth::user()->package_orders) {
                return view('panel.order.show', compact('order', 'message'));
            } else {
                $driverWithTime = Auth::user()->store->timeDelivery;
                return view('panel.order.show', compact('driverWithTime', 'order', 'message'));
            }
        } else {
            return view('panel.order.show', compact('order', 'message'));
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
        /*if(Auth::user()->hasRole('superadministrator')) {
            $order = Order::query()->find($id);
            if(!$order) {
                toastr()->error(__('alert.errorMessage'), __('alert.cancel'));
                return redirect()->route('order.index');
            }
            $userId = $order->created_by;
            $user = User::find($userId);
            if(!$user) {
                toastr()->error(__('alert.errorMessage'), __('alert.cancel'));
                return redirect()->route('order.index');
            }
            if($user->store) {
                if($user->store->get_list_days->number_of_order) {
                    return view('panel.order.edit', compact('order'));
                } else {
                    $driverWithTime = $user->store->timeDelivery;
                    return view('panel.order.edit', compact('driverWithTime', 'order'));
                }
            } else {
                return view('panel.order.edit', compact('order'));
            }
        }*/
        $order = Order::query()->where('created_by', Auth::user()->id)->find($id);
        if(!$order)
        {
            toastr()->error(__('alert.errorMessage'), __('alert.cancel'));
            return redirect()->route('order.index');
        }
        if($order->order_status == 4 || $order->order_status == 7 || $order->order_status == 5)
        {
            toastr()->error(__('alert.errorMessage'), __('alert.cancel'));
            return redirect()->route('order.index');
        }
        if(Auth::user()->store) {
            if(Auth::user()->package_orders()->exists() && $order->package_type == 0) {
                return view('panel.order.edit', compact('order'));
            } else if(Auth::user()->package_hours()->exists() && $order->package_type == 1) {
                $driverWithTime = Drivers::where(['user_id' => Auth::id(), 'status' => '1'])
                    ->with(['drivers'])
                    ->get();
                return view('panel.order.edit', compact('driverWithTime', 'order'));
            } else {
                return redirect()->back();
            }
        } else {
            return redirect()->back();
            return view('panel.order.edit', compact('order'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OrdersRequests $request, $id)
    {
        $order = Order::query()->find($id);
        if(!$order)
        {
            toastr()->error(__('alert.errorMessage'));
            return redirect()->route('order.index');
        }
        $userId = $order->created_by;
        $user = User::find($userId);
        // return $user;
        // format data
        $data = ['lat_from' => $request->lat_from,
            'long_from' => $request->long_from,
            'lat_to' => $request->lat_to,
            'long_to' => $request->long_to,
            'name' => $request->name,
            'phone' => $request->phone,
            'date' => Carbon::parse($request->date),
            'from_address' => $request->from_address,
            'to_address' => $request->to_address,
            'type_order' => $request->type_order,
            'description' => $request->description,
            'date_from' => Carbon::parse($request->date_from)->addHour(),
            'date_to' => Carbon::parse($request->date_to)->addHour(),
            'time_from' => $request->time_from,
            'time_to' => $request->time_to,
//            'created_by' => $user->id,
'order_reference' => $request->order_reference,
            'street' => $request->street,
            'sabil' => $request->sabil,
            'home' => $request->home,
            'block' => $request->block,
            'items' => $request->items,
            'totale_amount' => $request->totale_amount,

        ];
        if($order->package_type == 1) {
            $validator = $this->validate($request, [
                'delivery_id' => 'required',
            ]);
            $data = array_merge($data, ['delivery_id' => $request->delivery_id]);
        }
        /*if($user->store) {
            // get data vendor and packages
            $storeId = $user->store->id;
            $package_hours = $user->package_hours;
//            $package_orders = $user->package_orders;
//            $packageId = $package->id;

            // append data from query
            $data = array_merge($data, ['vendor_id' => $storeId,
                'vendor_list_package_id' => $package_hours->id]);

            // check packages
            if($package_hours){
                $data = array_merge($data, ['delivery_id' => $request->delivery_id]);
            }
        }*/

        // create new order
        $order->update($data);

        // success and redirect to index order
        toastr()->success(__('alert.successCreated'));
        return redirect()->route('order.index');
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
        $order = Order::find($id);
        if($order->package_type == 0) {
            $user = User::find($order->created_by);
            $packageId = VendorListPackages::where('user_id', Auth::id())
                ->where('number_of_order', '<>', null)
                ->where('status', '1')
                ->first();
            $order->delete();
            if(Auth::user()->store) {
                if($packageId->status == '0') {
                    $packageId->update(['status' => '1'])->update();
                }
                $packageId->increment('number_of_order', 1);
            }
            return response()->json([
                'success' => TRUE,
                'message' => __('alert.successUpdate')
            ]);
        } else if($order->package_type == 1) {
            $order->delete();
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
        if ($order) {
            // $delivery->update(['status' => '2', 'action_by' => Auth::id()]);
            // $vendorListPackages = VendorListPackages::find($delivery->vendor_list_package_id);
            // $vendorListPackages->increment('number_of_order',1);

            $user = User::find($order->created_by); //->get_list_days
            if($user->store()->exists()) {
                if($order->cancel == '0' && $user->store->get_list_days->number_of_order) {
                    $increment = VendorListPackages::query()
                        ->where('id', $user->store->get_list_days->id)
                        ->increment('number_of_order', 1);
                    $order->update(['cancel' => '1']);
                }
            }
            $order->delete();
            return response()->json([
                'success' => TRUE,
                'message' => __('alert.successUpdate')
            ]);
        } else {

        }
    }

    /**
     * set driver
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function driver($id)
    {
        //
        $order = Order::find($id);
        if (!$order || $order->status == '2' || $order->status == '1') {
            return redirect()->route('order.index');
        }
        return view('panel.order.driver', compact('order'));
    }

    /**
     * set driver
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setDriver(Request $request)
    {
        //
        $validator = $this->validate($request, [
            'order_id' => 'required|exists:orders,id',
            'delivery_id' => 'required|exists:deliveries,id',
        ]);
        $id = $request->order_id;
        $order = Order::find($id);
        if ($order) {
            $order->update(['status' => '1', 'action_by' => Auth::id(), 'delivery_id' => $request->delivery_id]);
        }
        return redirect()->route('order.index');
    }

    /**
     * change status
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
        $update = Order::find($id);
        if ($update) {
            if ($update->status) {
                $update->update(['status' => '0', 'action_by' => Auth::id()]);
            } else {
                $update->update(['status' => '1', 'action_by' => Auth::id()]);
            }
            return response()->json([
                'success' => TRUE,
                'message' => __('alert.successUpdate')
            ]);
        }
    }

    /**
     * change status
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function statusOrder(Request $request)
    {
        //
        /*if ($request->id == NULL || $request->status == NULL ) {
            return response()->json([
                'success' => False,
                'message' => __('alert.errorMessage')
            ]);
        }*/
        $order_id = $request->id;
        if(!$order_id) {
            return response()->json([
                'success' => False,
                'message' => __('alert.errorMessage')
            ]);
        }
        $order = Order::find($order_id);
        /*if($order->order_status = '5') {
            return response()->json([
                'success' => False,
                'message' => __('alert.errorMessage')
            ]);
        }*/
        if($order->order_status == 4 || $order->order_status == 7 || $order->order_status == 5) {
            return response()->json([
                'success' => False,
                'message' => __('alert.errorMessage')
            ]);
        }
        if($request->status) {
            $statusOreder = StatusSystem::where(['status' => '1', 'id' => $request->status])->first();
        }
        $driver = null;
        if($request->driver) {
            $driver = Delivery::find($request->driver);
        }

        // $statusCode = array('0', '1', '2', '3', '4');
        /*if(!$statusOreder) {
            return response()->json([
                'success' => False,
                'message' => __('alert.errorMessage')
            ]);
        }*/
        if($request->driver) {
            
            NotificationDriver::create([
                'order_id' => $order->id,
                'user_id' => $order->created_by,
                'driver_id' => $driver->id,
            ]);
            
            if($order->delivery_id != $driver->id) {
                $data = ['title' => 'Fox Delivery',
                    'order_id' => $order->id,
                    'body' => 'لديك طلب جديد #' . $order->id];
                $user = User::find($driver->user_id);
                $user->notify(new DriverNotification($data));
            }
            $order->update(['delivery_id' => $driver->id, 'action_by' => Auth::id()]);
            // return $user;
            
        }
        if($request->status) {
            $order->update(['order_status' => $statusOreder->id, 'action_by' => Auth::id(), 'color' => $statusOreder->color]);
            if ($statusOreder->decrement == '1') {
                $user = User::find($order->created_by); //->get_list_days
                if ($user->store()->exists()) {
                    if ($order->cancel == '0' && $user->package_orders->number_of_order) {
                        $increment = VendorListPackages::query()
                            ->where('id', $user->package_orders->id)
                            ->increment('number_of_order', 1);
                        $order->update(['cancel' => '1']);
                    }
                }
            }
            $orderAdmin = NotificationOrder::where('order_id', $order_id)->first();
            if ($orderAdmin) {
                $orderNotification = NotificationVendorOrder::create([
                    'vendor_id' => $orderAdmin->vendor_id,
                    'status' => $statusOreder->id,
                    'user_id' => $orderAdmin->user_id,
                    'order_id' => $orderAdmin->order_id,
                    'admin_id' => Auth::id(),
                ]);
            }
        }
        return response()->json([
            'success' => TRUE,
            'message' => __('alert.successUpdate')
        ]);
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
        $vendor = $request->get('vendor');
        $driver = $request->get('driver');

        $b_enabled = $request->get("b_enabled");

        if(Auth::user()->hasRole('superadministrator')) {
            $objects = Order::whereNull('deleted_at')
                ->orderBy('order_status', 'ASC')
                ->orderBy('id', 'DESC')
                ->with(['vendor', 'packages', 'delivery']);
        } else {
            $objects = Order::where('created_by', Auth::user()->id)
                ->whereNull('deleted_at')
                ->orderBy('order_status', 'ASC')
                ->orderBy('id', 'DESC')
                ->with(['vendor', 'packages', 'delivery']);
        }

        if(isset($s_name) && strlen($s_name) > 0)
            $objects = $objects->where('name', 'LIKE', '%'.$s_name.'%');

        if(isset($b_enabled))
            $objects = $objects->where('order_status', $b_enabled);

        if(isset($driver))
            $objects = $objects->where('delivery_id', $driver);

        if(isset($vendor))
            $objects = $objects->where('vendor_id', $vendor);

        return DataTables::of($objects)
            ->addColumn('id', function ($object) {
                return $object->id;
            })
            ->addColumn('name', function ($object) {
                return $object->name;
            })
            ->addColumn('from', function ($object) {
                // $googleMap = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $object->lat_from . ',' . $object->long_from . '&key=' . env('MAP_KEY');
                // $response = Http::get($googleMap);
                // $formatted_address = $response->json()['results'][0]['formatted_address'];
                // return $formatted_address;
                return $object->from_address;
            })
            ->addColumn('to', function ($object) {
                // $googleMap = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $object->lat_to . ',' . $object->long_to . '&key=' . env('MAP_KEY');
                // $response = Http::get($googleMap);
                // $formatted_address = $response->json()['results'][0]['formatted_address'];
                // return $formatted_address;
                // $ac = '<a class="btn btn-sm btn-default btn-text-primary btn-hover-primary btn-icon driver-btn"  data-lat="'.$object->lat_to.'" data-long="'.$object->long_to.'">
                //                     <i class="fas fa-car-side"></i>
                //                 </a>';
                return $object->to_address;
            })
            ->addColumn('phone', function ($object) {
                return $object->phone;
            })
            ->addColumn('vendor', function ($object) {
                // if($object->vendor()->exists()) {

                // }
                return $object->vendor()->exists() ? $object->vendor->name : '--';
            })
            ->addColumn('b_enabled', function ($object) {
                // return $object->delivery_id != null ? $object->delivery->name : '--';
                return $object->delivery()->exists() ? $object->delivery->name : '--';
            })
            ->addColumn('actions', function ($object) {
                // $object->status == '2' ? $disabled = 'onclick="return false;"' : $disabled = '';
                // $object->status == '2' ? $coloer = 'svg-icon-danger' : $coloer = 'svg-icon-primary';
                // $title = __('table.reject');
                $action = '';
            //     if(Auth::user()->hasRole('superadministrator')) {
            //               $action .= '
            // <a href="'.route("order.edit", $object->id).'" class="btn btn-sm btn-default btn-text-primary btn-hover-primary btn-icon">
            //     <span class="svg-icon svg-icon-md">
            //         <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
            //             <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
            //                 <rect x="0" y="0" width="24" height="24"/>
            //                 <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"/>
            //                 <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"/>
            //             </g>
            //         </svg>
            //     </span>
            // </a>';
            //             }
                if(Auth::user()->hasPermission('order_delete')) {
                           $action .= '<a href="javascript:;" onclick="DeleteConfirm('.$object->id.',\'' . route("order.destroy", $object->id).  '\')" class="btn btn-sm btn-default btn-text-primary btn-hover-primary btn-icon" title="Delete">
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
                // return Auth::user()->hasPermission('order_delete');
                if(Auth::user()->hasRole('superadministrator')) {
                    $action .= '<div class="dropdown dropdown-inline" id="driver">
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
                                                <a href="'.route("order.show", $object->id).'" class="navi-link">
                                                    <span class="navi-icon"><i class="la la-eye"></i></span>
                                                    <span class="navi-text">'.trans("table.action_show").'</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                
                                
                                
                                <a href="'.route("order.printPdf", $object->id).'" class="btn btn-sm btn-default btn-text-primary btn-hover-primary btn-icon">

                                    <span class="svg-icon svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo1\dist/../src/media/svg/icons\Devices\Printer.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <path d="M16,17 L16,21 C16,21.5522847 15.5522847,22 15,22 L9,22 C8.44771525,22 8,21.5522847 8,21 L8,17 L5,17 C3.8954305,17 3,16.1045695 3,15 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,15 C21,16.1045695 20.1045695,17 19,17 L16,17 Z M17.5,11 C18.3284271,11 19,10.3284271 19,9.5 C19,8.67157288 18.3284271,8 17.5,8 C16.6715729,8 16,8.67157288 16,9.5 C16,10.3284271 16.6715729,11 17.5,11 Z M10,14 L10,20 L14,20 L14,14 L10,14 Z" fill="#000000"/>
                                                <rect fill="#000000" opacity="0.3" x="8" y="2" width="8" height="2" rx="1"/>
                                            </g>
                                        </svg><!--end::Svg Icon-->
                                    </span>
                			</a>
                			';
                			if($object->order_status == 1 || $object->order_status == 2 || $object->order_status == 3 || $object->order_status == 6) {
                			    $action .= '<a class="btn btn-sm btn-default btn-text-primary btn-hover-primary btn-icon driver-btn"  data-id="'.$object->id.'">
                                    <i class="fas fa-car-side"></i>
                                </a>';
                			    
                			}
                        } elseif(Auth::user()->hasPermission('order_update')) {
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
                                                <a href="'.route("order.edit", $object->id).'" class="navi-link">
                                                    <span class="navi-icon"><i class="la la-list-alt"></i></span>
                                                    <span class="navi-text">'.trans("table.action_edit").'</span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="'.route("order.show", $object->id).'" class="navi-link">
                                                    <span class="navi-icon"><i class="la la-eye"></i></span>
                                                    <span class="navi-text">'.trans("table.action_show").'</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>';
                        } elseif(Auth::user()->hasPermission('package_create')){
                            $action .= '<a href="'.route("order.printPdf", $object->id).'" class="btn btn-sm btn-default btn-text-primary btn-hover-primary btn-icon">

                    <span class="svg-icon svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo1\dist/../src/media/svg/icons\Devices\Printer.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"/>
                                <path d="M16,17 L16,21 C16,21.5522847 15.5522847,22 15,22 L9,22 C8.44771525,22 8,21.5522847 8,21 L8,17 L5,17 C3.8954305,17 3,16.1045695 3,15 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,15 C21,16.1045695 20.1045695,17 19,17 L16,17 Z M17.5,11 C18.3284271,11 19,10.3284271 19,9.5 C19,8.67157288 18.3284271,8 17.5,8 C16.6715729,8 16,8.67157288 16,9.5 C16,10.3284271 16.6715729,11 17.5,11 Z M10,14 L10,20 L14,20 L14,14 L10,14 Z" fill="#000000"/>
                                <rect fill="#000000" opacity="0.3" x="8" y="2" width="8" height="2" rx="1"/>
                            </g>
                        </svg><!--end::Svg Icon-->
                    </span>
			</a>';
                        } else {
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
                                                <a href="'.route("order.show", $object->id).'" class="navi-link">
                                                    <span class="navi-icon"><i class="la la-eye"></i></span>
                                                    <span class="navi-text">'.trans("table.action_show").'</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                ';
                        }
                return $action;
            })
            ->addColumn('dt_creation_date', function ($object) {
                return Carbon::parse($object->created_at)->toDateString();
            })
            ->addColumn('order_status', function ($object) {
                return '<span class="label label-inline label-light-'.$object->statusOrder->cards_color.' font-weight-bold">'.$object->statusOrder->name.'</span>';
            })
            ->addIndexColumn()
            ->rawColumns(['actions', 'b_enabled', 'order_status'])
            ->make(true);
    }

    public function employeeOrderindex() {
        // return 1;
        return view('panel.employee.order');
    }

    public function employeeOrderDatatable(Request $request) {
        //
        $data = $request->all();
        $s_name = $request->get('s_name');
        $s_mobile_number = $request->get('s_mobile_number');
        $s_email = $request->get('s_email');
        $vendor = $request->get('vendor');
        $driver = $request->get('driver');

        $b_enabled = $request->get("b_enabled");
        $v = Vendor::where('id', Auth::user()->vendor_id)->first();
            $objects = Order::whereNull('deleted_at')
                ->where('vendor_id', $v->id)
                ->orderBy('order_status', 'ASC')
                ->with(['vendor', 'packages', 'delivery']);
        if(isset($s_name) && strlen($s_name) > 0)
            $objects = $objects->where('name', 'LIKE', '%'.$s_name.'%');
        if(isset($b_enabled))
            $objects = $objects->where('order_status', $b_enabled);

       /* if(isset($driver))
            $objects = $objects->where('delivery_id', $driver);*/

        /*if(isset($vendor))
            $objects = $objects->where('vendor_id', $vendor);*/

        return DataTables::of($objects)
            ->addColumn('id', function ($object) {
                return $object->id;
            })
            ->addColumn('name', function ($object) {
                return $object->name;
            })
            ->addColumn('from', function ($object) {
                // $googleMap = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $object->lat_from . ',' . $object->long_from . '&key=' . env('MAP_KEY');
                // $response = Http::get($googleMap);
                // $formatted_address = $response->json()['results'][0]['formatted_address'];
                // return $formatted_address;
                return $object->from_address;
            })
            ->addColumn('to', function ($object) {
                // $googleMap = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $object->lat_to . ',' . $object->long_to . '&key=' . env('MAP_KEY');
                // $response = Http::get($googleMap);
                // $formatted_address = $response->json()['results'][0]['formatted_address'];
                // return $formatted_address;
                // $ac = '<a class="btn btn-sm btn-default btn-text-primary btn-hover-primary btn-icon driver-btn"  data-lat="'.$object->lat_to.'" data-long="'.$object->long_to.'">
                //                     <i class="fas fa-car-side"></i>
                //                 </a>';
                return $object->to_address;
            })
            ->addColumn('phone', function ($object) {
                return $object->phone;
            })
            ->addColumn('vendor', function ($object) {
                // if($object->vendor()->exists()) {

                // }
                return $object->vendor()->exists() ? $object->vendor->name : '--';
            })
            ->addColumn('b_enabled', function ($object) {
                // return $object->delivery_id != null ? $object->delivery->name : '--';
                return '<span class="label label-inline label-light-'.$object->statusOrder->cards_color.' font-weight-bold">'.$object->statusOrder->name.'</span>';
            })
            ->addColumn('delivery', function ($object) {
                // return $object->delivery_id != null ? $object->delivery->name : '--';
                return $object->delivery()->exists() ? $object->delivery->name : '--';
            })
            ->addColumn('actions', function ($object) {
                // $object->status == '2' ? $disabled = 'onclick="return false;"' : $disabled = '';
                // $object->status == '2' ? $coloer = 'svg-icon-danger' : $coloer = 'svg-icon-primary';
                // $title = __('table.reject');
                $action = '<a href="'.route("order.printPdf.employee", $object->id).'" class="navi-link">
                                                    <span class="svg-icon svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo1\dist/../src/media/svg/icons\Devices\Printer.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"/>
                                <path d="M16,17 L16,21 C16,21.5522847 15.5522847,22 15,22 L9,22 C8.44771525,22 8,21.5522847 8,21 L8,17 L5,17 C3.8954305,17 3,16.1045695 3,15 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,15 C21,16.1045695 20.1045695,17 19,17 L16,17 Z M17.5,11 C18.3284271,11 19,10.3284271 19,9.5 C19,8.67157288 18.3284271,8 17.5,8 C16.6715729,8 16,8.67157288 16,9.5 C16,10.3284271 16.6715729,11 17.5,11 Z M10,14 L10,20 L14,20 L14,14 L10,14 Z" fill="#000000"/>
                                <rect fill="#000000" opacity="0.3" x="8" y="2" width="8" height="2" rx="1"/>
                            </g>
                        </svg><!--end::Svg Icon-->
                    </span>

                                                </a>';
                // return Auth::user()->hasPermission('order_delete');

                return $action;
            })
            ->addColumn('dt_creation_date', function ($object) {
                return Carbon::parse($object->created_at)->toDateString();
            })
            ->addColumn('order_status', function ($object) {
                return '<span class="label label-inline label-light-'.$object->statusOrder->cards_color.' font-weight-bold">'.$object->statusOrder->name.'</span>';
            })
            ->addIndexColumn()
            ->rawColumns(['actions', 'b_enabled', 'order_status'])
            ->make(true);
    }

    /**
     * read notification super admin
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function readNotification($id) {
        $noti = NotificationOrder::find($id);
        if($noti) {
            $noti->update([
                'admin_id' => Auth::id(),
                'read'     => '1'
            ]);
            return redirect()->route('order.show', $noti->order_id);
            // $this->show($noti->order_id);
        }
    }

    /**
     * read notification vendor
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function readNotificationVendor($id) {
        $noti = NotificationVendorOrder::find($id);
        if($noti) {
            $noti->update([
                'read'     => '1'
            ]);
            return redirect()->route('order.show', $noti->order_id);
        }
    }
}
