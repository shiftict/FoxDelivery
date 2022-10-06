<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Delivery\Deliveryresource;
use App\Http\Resources\Delivery\Deliverysresource;
use App\Http\Resources\Delivery\DriversResource;
use App\Models\Delivery;
use App\Models\DeliveryTime;
use App\Models\Drivers;
use App\Models\MethodShipping;
use App\Models\User;
use App\Models\Vendor;
use App\Transformers\Delivery\DeliveryTransformers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;
use Auth;

class DeliveryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:delivery_read'])->only('index');
        $this->middleware(['permission:delivery_create'])->only('create');
        $this->middleware(['permission:delivery_delete'])->only('destroy');
        $this->middleware(['permission:delivery_update'])->only(['edit|status']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        return view('panel.delivery.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $methodsShippings = MethodShipping::where('status', '1')
            ->get();
        return view('panel.delivery.create', compact('methodsShippings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function schedulingDrivers()
    {
        //
        $vendors = Vendor::query()
            // ->whereDate('created_at', Carbon::today())
            ->where('status', '1')
            ->whereHas('package_hours')
            ->with(['drivers.drivers', 'user', 'package_hours', 'timeDelivery'])->get();
        return view('panel.delivery.scheduling', compact('vendors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function schedulingDriversVue()
    {
        //
        $vendors = Vendor::query()
        
            ->where('status', '1')
            ->whereHas('package_hours')
            ->with(['drivers.drivers', 'user', 'package_hours', 'timeDelivery', 'timeDeliverySchaduling'])->get();
        if($vendors) {
            return response()->json([
                'success' => true,
                'data' => $vendors
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => __('alert.errorMessage')
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function schedulingDriversInsertByDate(Request $request)
    {
        //
//        return $request->all();
        $vendors = Drivers::query()
            ->withTrashed()
            ->whereDate('created_at', Carbon::parse($request->data))
            ->get();
        $checkDate = Drivers::query()
            ->whereDate('created_at', Carbon::today())
            ->get();
            // return Carbon::parse($request->data)->addDay();
            // return $vendors->count();
            if($checkDate->count() > 0) {
                return response()->json([
                    'success' => false,
                    'code' => 666,
                    'message' => __('alert.validDriver')
                ]);
            }
            // return $vendors;
            if($vendors->count() > 0) {
                $vendors_delete = Drivers::whereDate('created_at', $request->data)->delete();
                foreach($vendors as $v) {
                    $data = [
                        'deliveries_id' => $v->deliveries_id,
                        'vendor_id' => $v->vendor_id,
                        'user_id' => $v->user_id,
                        'time_first_from' => Carbon::parse($v->time_first_from),
                        'start_secound_shift' => $v->deliveries_id ? Carbon::parse($v->start_secound_shift) : null,
                        'time_first_to' => Carbon::parse($v->time_first_to),
                        'end_secound_shift' => $v->end_secound_shift ? Carbon::parse($v->end_secound_shift) : null,
                        'user_drivers_id' => $v->user_drivers_id,
                        'status' => '1',
                    ];
                    $create = Drivers::query()->create($data);
                }
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'data' => ''
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'code' => 404,
                    'message' => __('alert.validDriverNotFoundThisDate')
                ]);
            }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function schedulingDriversInsert(Request $request)
    {
        //
        Drivers::query()->delete();
        foreach($request->all() as $data) {
            if($data != null) {
                echo $data['drivers'];
                if($data['drivers'] && $data['id'] && $data['tim_from_first'] && $data['tim_to_first']) {
                    $vendors = Vendor::where('id', $data['id'])->with(['package_hours'])->first();
                    $drvier_object = Delivery::find($data['drivers']);
                    if($drvier_object) {
                        $drvier = User::where('id', $drvier_object->user_id)->first();
                        if($drvier) {
                            $dataInsert = [
                                'deliveries_id' => $data['drivers'],
                                'vendor_id' => $data['id'],
                                'user_id' => $vendors->user_id,
                                'time_first_from' => Carbon::parse($data['tim_from_first']),
                                'start_secound_shift' => $data['tim_from_secound'] ? Carbon::parse($data['tim_from_secound']) : null,
                                'time_first_to' => Carbon::parse($data['tim_to_first']),
                                'end_secound_shift' => $data['tim_to_secound'] ? Carbon::parse($data['tim_to_secound']) : null,
                                'user_drivers_id' => $drvier->id,
                                'status' => '1',
                            ];
                            $create = Drivers::query()->create($dataInsert);
                        }
                    }
                }
            } else {
                return 0;
            }
        }
        $vendors = Vendor::query()
            ->where('status', '1')
            ->whereHas('package_hours')
            ->with(['drivers.drivers', 'user', 'package_hours', 'timeDelivery'])->get();
//        return $vendors;
        return view('panel.delivery.scheduling', compact('vendors'));
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
        // dd($request->all());
        $validator = $this->validate($request, [
            'name' => 'required|max:255',
            'phone' => 'required|unique:users,mobile|max:15',
            'password' => 'required|min:6|max:20',
            'method_id' => 'required|exists:method_shippings,id',
            'status' => 'required|min:0|max:1',
            'code' => 'required|unique:users,code|max:15',
        ]);
        $data = $request->only(['name', 'phone', 'method_id', 'status', 'code']);
        // return $data;
        $dataUser = $request->only(['name', 'code']);
        // return $data;
        $password = Hash::make($request->password);
        $userDataAppend = array_merge($dataUser, ['password' => $password, 'mobile' => $request->phone, 'email' => $request->phone.'@driver.com']);
        $user = User::create($userDataAppend);
        $dataAppend = array_merge($data, ['user_id' => $user->id, 'email' => $request->phone.'@driver.com']);
        $newMethods = Delivery::create($dataAppend);
        toastr()->success(__('alert.successCreated'), __('alert.success'));
        return redirect()->route('delivery.index');
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
        $delivery = Delivery::query()->find($id);
        if(!$delivery)
        {
            toastr()->error(__('alert.errorMessage'), __('alert.cancel'));
            return redirect()->route('delivery.index');
        }
        $methodsShippings = MethodShipping::query()->where('status', '1')->get();
        return view('panel.delivery.edit', compact('delivery', 'methodsShippings'));
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
        $delivery = Delivery::query()->find($id);
        if(!$delivery)
        {
            toastr()->error(__('alert.errorMessage'), __('alert.cancel'));
            return redirect()->route('delivery.index');
        }
        $validator = $this->validate($request, [
            'name' => 'required|max:255',
            'phone' => 'required|max:15|unique:users,mobile,' . $delivery->user_id,
            'method_id' => 'required|exists:method_shippings,id',
            'status' => 'required|min:0|max:1'
        ]);
        if($request->password) {
            $new_pass = bcrypt($request->password);
            User::query()->where('id', $delivery->user_id)->update(['mobile' => $request->phone, 'email' => $request->phone.'@driver.com', 'password' => $new_pass]);
        } else {
            User::query()->where('id', $delivery->user_id)->update(['mobile' => $request->phone, 'email' => $request->phone.'@driver.com']);
        }
        
        $data = $request->only(['name', 'phone', 'method_id', 'status']);
        $dataAppend = array_merge($data, ['email' => $request->phone.'@driver.com']);
        $delivery->update($dataAppend);
        User::query()->where('id', $delivery->user_id)->update(['name' => $request->name]);
        toastr()->success(__('alert.successUpdate'), __('alert.success'));
        return redirect()->route('delivery.index');
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
        $delivery = Delivery::find($id);
        if ($delivery) {
            $delivery->delete();
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
        $update = Delivery::find($id);
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
     *
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function datatable(Request $request){
        $data = $request->all();
        $s_name = $request->get('s_name');
        $s_mobile_number = $request->get('s_mobile_number');
        $s_email = $request->get('s_email');
        $b_enabled = $request->get("b_enabled");

        $objects = Delivery::whereNull('deleted_at');

        if(isset($s_name) && strlen($s_name) > 0)
            $objects = $objects->where('name', 'LIKE', $s_name.'%');

        if(isset($s_mobile_number) && strlen($s_mobile_number) > 0)
            $objects = $objects->where('phone', 'LIKE',  '%' . $s_mobile_number . '%');

        if(isset($s_email) && strlen($s_email) > 0)
            $objects = $objects->where('email', 'LIKE',  '%' . $s_email . '%');

        if(isset($b_enabled) && $b_enabled == 1)
            $objects = $objects->where('status', $b_enabled);

        if(isset($b_enabled) && $b_enabled == 2)
            $objects = $objects->where('status', '0');

        return DataTables::of($objects)
            ->addColumn('methodShipping', function ($object) {
                return $object->methodShipping->name;
            })
            ->addColumn('b_enabled', function ($object) {
                $is_enabled = '';
                $is_enabled .= '<div class="col-12">';
                $is_enabled .= '<span class="switch switch-sm switch-outline switch-icon switch-primary"><label>';
                if ($object->status == TRUE) {
                    $is_enabled .= '<input onclick="ch_st(' . $object->id . ',\'' . route('delivery.status') . '\',\'' . csrf_token() . '\')" type="checkbox" checked="checked" name="select" />';
                } else {
                    $is_enabled .= '<input onclick="ch_st(' . $object->id . ',\'' . route('delivery.status') . '\',\'' . csrf_token() . '\')" type="checkbox" name="select" />';
                }
                $is_enabled .= '<span></span></label>';
                $is_enabled .= '</span>';
                $is_enabled .= '</div>';
                return $is_enabled;
            })

            ->addColumn('actions', function ($object) {
                $action = '';
                if(Auth::user()->hasPermission('delivery_delete')) {
                    $action .= '
                <a href="javascript:;" onclick="DeleteConfirm('.$object->id.',\'' . route("delivery.destroy", $object->id).  '\')" class="btn btn-sm btn-default btn-text-primary btn-hover-primary btn-icon" title="Delete">
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
            if(Auth::user()->hasPermission('delivery_update')) {
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
                            <a href="'.route("delivery.edit", $object->id).'" class="navi-link">
                                <span class="navi-icon"><i class="la la-list-alt"></i></span>
                                <span class="navi-text">'.trans("table.action_edit").'</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>';
        }
                return $action;
            })
            ->addColumn('dt_creation_date', function ($object) {
                return Carbon::parse($object->dt_created_date)->toDateString();
            })
            ->addIndexColumn()
            ->rawColumns(['actions', 'b_enabled'])
            ->make(true);
    }

    /**
     * get drivers by vue.js
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function vueDrivers(Request $request) {
        // query get delivery by time
        $delivery = Delivery::query()
//            ->WhereDoesntHave('timeDelivery')
//            ->orWhereHas('timeDelivery', function($q) use($request){
//                $q->whereTime('time_from', '<', $request->start_shift)
//                    ->whereTime('time_to', '>', $request->end_shift)
//                    ->orWhereNotBetween('time_from', [$request->start_shift, $request->end_shift])
//                    ->orWhereNotBetween('time_to', [$request->start_shift, $request->end_shift]);
//                $q->WhereNotBetween('time_from', [$request->start_shift, $request->end_shift])
//                ->WhereNotBetween('time_to', [$request->start_shift, $request->end_shift]);
//        })
        ->get();
        // create format collection
        $delivery = Deliveryresource::collection($delivery);
        // passing to option dropdown at blade vendor
//        app()->setLocale(app()->getLocale());
        $vehicle = Deliverysresource::collection(MethodShipping::where('status', '1')->get());
        return response()->json(['delivery' => $vehicle]);
    }

    public function vueDriversNew(Request $request) {
        $drivers = Drivers::where(['user_id' => $request->id, 'status' => '1'])
            ->with(['drivers'])
            ->get();
        $drivers_collection = DriversResource::collection($drivers);
        return response()->json(['delivery' => $drivers_collection]);
    }

    public function vueDriversAdminNew(Request $request) {
        $drivers = Delivery::where('status', '1')
            ->get();
        $drivers_collection = Deliverysresource::collection($drivers);
        return response()->json(['delivery' => $drivers_collection]);
    }
}
