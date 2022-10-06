<?php

namespace App\Http\Controllers\Api\V1\Vendors;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\LoginResource;
use App\Http\Resources\Api\V1\VendorsResource;
use App\Http\Resources\Api\V1\AreaResource;
use App\Models\User;
use App\Models\Area;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use App\Models\Order;
use App\Models\NotificationOrder;
use App\Models\StatusSystem;

class VendorsOrderController extends ApiController
{
    /**
     * logout driver.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // get object driver
        $vendor = $request->user();
        // return $vendor;
        $request->validate([
            'i_type_order' => ['required', 'in:0,1'],
            's_from_address_lat' => ['required'],
            's_from_address_long' => ['required'],
            's_to_address_lat' => ['required'],
            's_to_address_long' => ['required'],
            's_name_of_customer' => ['required'],
            's_phone' => ['required', 'numeric'],
            's_house' => ['required'],
            's_street' => ['required'],
            's_sabil' => ['required'],
            's_block' => ['required'],
            's_description' => ['required'],
            'd_pick_up_date' => ['required', 'date_format:Y-m-d'],
            't_time_from' => ['required', 'date_format:H:i'],
            'd_delivery_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:d_pick_up_date'],
            't_time_to' => ['required', 'date_format:H:i', 'after:t_time_from'],
            'i_city' => ['required', 'exists:cities,id'],
            'i_items' => ['required'],
            'i_payments_method' => ['required', 'in:0,1'],
            'i_totale_amount' => ['required'],
        ]);
        $statusOreder = StatusSystem::where(['status' => '1', 'defulte_status' => '1'])->first();
                    $formatData = [
                    'items' => $request['i_items'],
                    'payment_method' => $request['i_payments_method'],
                    'order_reference' => $request['order_reference'],
                    'totale_amount' => $request['i_totale_amount'],
                    'city_id' => $request['i_city'],
                    'lat_from' => $request['s_from_address_lat'],
                    'long_from' => $request['s_from_address_long'],
                    'lat_to' => $request['s_to_address_lat'],
                    'long_to' => $request['s_to_address_long'],
                    'name' => $request['s_name_of_customer'],
                    'phone' => $request['s_phone'],
                    'date_from' => Carbon::parse($request['d_pick_up_date'])->addHour(),
                    'date_to' => Carbon::parse($request['d_delivery_date'])->addHour(),
                    'time_from' => $request['t_time_from'],
                    'time_to' => $request['t_time_to'],
                    'from_address' => $request['s_city_from'],
                    'to_address' => $request['s_city_to'],
                    'type_order' => $request['i_type_order'],
                    'description' => $request['about'],
                    'block' => $request['s_block'],
                    'home' => $request['s_house'],
                    'sabil' => $request['s_sabil'],
                    'street' => $request['s_street'],
                    'package_type' => '0', // only 0 its peer hours
                    'created_by' => $vendor->id,
                    'order_status' => $statusOreder->id,
                    'vendor_list_package_id' => $vendor->store->package_order->id,
                    'vendor_id' => $vendor->store->id,
                ];
                
                $newOrder = Order::create($formatData);
                NotificationOrder::query()->create([
                    'vendor_id' => $vendor->store->id,
                    'user_id' => $vendor->id,
                    'order_id' => $newOrder->id,
                ]);
                
        // return success message
        return $this->successResponse(null, __('api.create_order_successfully'), ResponseAlias::HTTP_OK);
    }

    /**
     * get area with city.
     *
     * @return \Illuminate\Http\Response
     */
    public function lockUp(Request $request)
    {
        $area = Area::with('cities')->get();
        // return success message
        // return $area;
        $area_object = AreaResource::collection($area);
        return $this->successResponse($area_object, null, ResponseAlias::HTTP_OK);
    }
}
