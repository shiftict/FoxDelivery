<?php

namespace App\Http\Controllers\Api\V1\Drivers;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\DetailsOrderResource;
use App\Http\Resources\Api\V1\PendingOrderResource;
use App\Models\NotificationVendorOrder;
use App\Models\Order;
use App\Models\StatusSystem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Carbon\Carbon;

class OrderController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        // query get pending order by driver
        $order = $request->user()->delivery->allOrder;

        // return data
        if ($order->count() == 0 || $order->count() < 0) {
            return $this->errorResponse(null, __('api.not_found_order'),ResponseAlias::HTTP_NOT_FOUND);
        }
        // success data
        $order_object = PendingOrderResource::collection($order);
        return $this->successListResponse($order_object,null,ResponseAlias::HTTP_OK);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pendingOrders(Request $request)
    {
        // query get pending order by driver
        $order = $request->user()->delivery->pendingOrder;

        // return data
        if ($order->count() == 0 || $order->count() < 0) {
            return $this->errorResponse(null, __('api.not_found_order'),ResponseAlias::HTTP_NOT_FOUND);
        }
        // success data
        $order_object = PendingOrderResource::collection($order);
        return $this->successListResponse($order_object,null,ResponseAlias::HTTP_OK);
    }
    
    public function orderActive(Request $request) {
        // query get pending order by driver
        $order = $request->user()->delivery->active_order;
        $response = [
            'status' => true,
            'code' => 200,
            'message' => null,
            'data' => ['count_active_order' => count($order), 'has_active_order' => count($order) > 0 ? true : false]
        ];
        return response()->json($response, 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request, $id)
    {
        // validate
        $request->validate([
            'i_status' => ['required', 'exists:order_status,id'],
        ]);
        // query get order by driver && id
        $order = Order::where(['delivery_id' => $request->user()->delivery->id, 'id' => $id])->first();

        // query get status
        $status = StatusSystem::where(['status' => '1', 'id' => $request->i_status])->first();

        // return data
        if (!$status) {
            return $this->errorResponse(null, __('api.not_found_status'),ResponseAlias::HTTP_NOT_FOUND);
        }

        // return data
        if (!$order) {
            return $this->errorResponse(null, __('api.not_found_order'),ResponseAlias::HTTP_NOT_FOUND);
        }
        
        if($order->order_status == 4 || $order->order_status == 7 || $order->order_status == 5) {
            return $this->errorResponse(null, __('alert.errorMessage'),ResponseAlias::HTTP_NOT_FOUND);
        }

        // get admin
        $users = User::whereRoleIs('Superadministrator')->first();

        /*if($request->i_status == 2){
            $start  = new Carbon($order->time_from);
            $end    = new Carbon(Carbon::now());
            // return $end->format('H:s:i');
            $deff = $start->diffInMinutes($end);
            if($start > $end) {
                $order->update(['date_from_driver' => carbon::now(), 'time_from_driver' => carbon::now()->format('H:s:i'), 'delay_pic_up' => '0']);
            } else if(env("DELAY_MINUTES", "15") < $deff){
                $order->update(['date_from_driver' => carbon::now(), 'time_from_driver' => carbon::now()->format('H:s:i'), 'delay_pic_up' => '1']);
            }
        }*/
        
        if($request->i_status == 2){
            User::where('id',$request->user()->id)->update(['is_online' => '1']);
            //  $request->user()->update(['is_online' => '1']);
            // return $request->user();
            $start  = new Carbon($order->time_from);
            $end    = new Carbon(Carbon::now());
            
            $start_date = new Carbon($order->date_from);
            $end_date    = new Carbon(Carbon::now());
            // return $end->format('H:s:i');
            $deff = $start->diffInMinutes($end);
            $deff_date = $start_date->diffInDays($end_date);
            // return $deff_date;
            // $messageSms = 'جاري توصيل طلبك ، يمكنك متابعة السائق من خلال الرابط :' . config('app.url') . '/tracking-order/' . $order->id;
            $messageSms = '('.$order->vendor->name.') order #'. $order->id .' is picked up by FoxDelivery and on the way now. You can track it on : '. config('app.url') . '/tracking-order/' . $order->id;
            $hello = 'url tracking your order : ' . config('app.url') . '/tracking-order/' . $order->id;
            $url = 'http://smsbox.com/smsgateway/services/messaging.asmx/Http_SendSMS';
            
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,
                        'username=foxdelivery&password=fox_12345&customerid=3086&sendertext=FOXDELIVERY&messagebody='.$messageSms.'&recipientnumbers=965'.$order->phone.'&defdate=&isblink=false&isflash=false');
            // Receive server response ...
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            $server_output = curl_exec($ch);
            
            curl_close ($ch);
            // return '1';
            
            if($start_date > $end_date) {
                $order->update(['date_from_driver' => carbon::now(), 'time_from_driver' => carbon::now()->format('H:s:i'), 'delay_pic_up' => '0', 'order_status' => $request->i_status]);
            } else if($start > $end && $deff_date == 0) {
                $order->update(['date_from_driver' => carbon::now(), 'time_from_driver' => carbon::now()->format('H:s:i'), 'delay_pic_up' => '0', 'order_status' => $request->i_status]);
            } else if(env("DELAY_MINUTES", "15") > $deff){
                $order->update(['date_from_driver' => carbon::now(), 'time_from_driver' => carbon::now()->format('H:s:i'), 'delay_pic_up' => '0', 'order_status' => $request->i_status]);
            } else {
                $order->update(['date_from_driver' => carbon::now(), 'time_from_driver' => carbon::now()->format('H:s:i'), 'delay_pic_up' => '1', 'order_status' => 6]);
            }
            
        } else if($request->i_status == 4) {
            $start  = new Carbon($order->time_to);
            $end    = new Carbon(Carbon::now());
            
            $start_date = new Carbon($order->date_to);
            $end_date    = new Carbon(Carbon::now());
            // return $end->format('H:s:i');
            $deff = $start->diffInMinutes($end);
            $deff_date = $start_date->diffInDays($end_date);
            // return $deff_date;
            if($start_date > $end_date) {
                $order->update(['date_to_driver' => carbon::now(), 'time_to_driver' => carbon::now()->format('H:s:i'), 'delay_delivery' => '0', 'order_status' => $request->i_status]);
            } else if($start > $end && $deff_date == 0) {
                $order->update(['date_to_driver' => carbon::now(), 'time_to_driver' => carbon::now()->format('H:s:i'), 'delay_delivery' => '0', 'order_status' => $request->i_status]);
            } else if(env("DELAY_MINUTES", "15") > $deff){
                $order->update(['date_to_driver' => carbon::now(), 'time_to_driver' => carbon::now()->format('H:s:i'), 'delay_delivery' => '0', 'order_status' => $request->i_status]);
            } else {
                $order->update(['date_to_driver' => carbon::now(), 'time_to_driver' => carbon::now()->format('H:s:i'), 'delay_delivery' => '1', 'order_status' => 7]);
            }
        } else if($request->i_status == 2) {
            $order->update(['pick_up' => carbon::now(), 'delay_delivery' => '0', 'order_status' => $request->i_status]);
        } else {
            // update status order
            $order->update(['order_status' => $request->i_status]);
        }
            // push notification to user
            $orderNotification = NotificationVendorOrder::create([
                'vendor_id' => $order->vendor_id,
                'status' => $request->i_status,
                'user_id' => $order->created_by,
                'order_id' => $order->id,
                'admin_id' => $users->id,
            ]);
    
            // success data
            $order_object = new DetailsOrderResource($order);
            return $this->successResponse($order_object,null,ResponseAlias::HTTP_OK);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return $this->errorResponse(null, null,ResponseAlias::HTTP_NOT_FOUND);
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
        return $this->errorResponse(null, null,ResponseAlias::HTTP_NOT_FOUND);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        //
        // query get pending order by driver
        $order = Order::where(['delivery_id' => $request->user()->delivery->id, 'id' => $id])->first();

        // return data
        if (!$order) {
            return $this->errorResponse(null, __('api.not_found_order'),ResponseAlias::HTTP_NOT_FOUND);
        }
        // success data
        $order_object = new DetailsOrderResource($order);
        return $this->successResponse($order_object,null,ResponseAlias::HTTP_OK);
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
        return $this->errorResponse(null, null,ResponseAlias::HTTP_NOT_FOUND);
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
        return $this->errorResponse(null, null,ResponseAlias::HTTP_NOT_FOUND);
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
        return $this->errorResponse(null, null,ResponseAlias::HTTP_NOT_FOUND);
    }
}
