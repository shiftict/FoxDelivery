<?php

namespace App\Http\Controllers\Api\V1\Drivers;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\NotificationsResource;
use App\Models\NotificationDriver;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use App\Http\Resources\Api\V1\sliderApkResource;
use App\Models\sliderApp;

class NotificationController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // query get pending order by driver
        $notification = $request->user()->delivery->driver_notification;
        NotificationDriver::where('driver_id', $request->user()->delivery->id)->update(['read_at' => Carbon::now()]);
        // return data
        if ($notification->count() == 0 || $notification->count() < 0) {
            return $this->errorResponse(null, __('api.not_found_notification'),ResponseAlias::HTTP_NOT_FOUND);
        }

        // success data
        $object_notification = NotificationsResource::collection($notification);
        return $this->successListResponse($object_notification, null, ResponseAlias::HTTP_OK);
    }
    
    public function countNotification(Request $request) {
        $count['count'] = (int)NotificationDriver::where('read_at', null)->where('driver_id', $request->user()->delivery->id)->count();
        $response = [
            'status' => true,
            'code' => 200,
            'message' => null,
            'data' => ['count' => (int)NotificationDriver::where('read_at', null)->where('driver_id', $request->user()->delivery->id)->count()]
        ];
        return response()->json($response, 200);
    }

    /**
     * Show the form for creating a new resource.
     *(int)NotificationDriver::where('read_at', null)->count()
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
    public function show($id)
    {
        //
        return $this->errorResponse(null, null,ResponseAlias::HTTP_NOT_FOUND);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function read_at_notification(Request $request)
    {
        // validate
        $request->validate([
            'i_id' => ['required', 'exists:notification_driver_orders,id'],
        ]);
        // query get order by driver && id
        $notificationDrviers = NotificationDriver::where(['driver_id' => $request->user()->delivery->id, 'id' => $request->i_id])->first();

        // return data
        if (!$notificationDrviers) {
            return $this->errorResponse(null, __('api.not_found_notification'),ResponseAlias::HTTP_NOT_FOUND);
        }

        // update status order
        $notificationDrviers->update(['read_at' => Carbon::now()]);

        // get all notification
        $allNotification = NotificationDriver::where(['driver_id' => $request->user()->delivery->id])->get();

        // success data
        $allNotification_object = NotificationsResource::collection($allNotification);
        return $this->successResponse(['data' => ['notification' => $allNotification_object,
            'i_un_read' => (int)NotificationDriver::where('read_at', null)->count()]],null,ResponseAlias::HTTP_OK);
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
    
    public function sliderApk() {
        $attach = sliderApp::get();
        // success data
        $collection_file = sliderApkResource::collection($attach);
        return $this->successListResponse($collection_file, null, ResponseAlias::HTTP_OK);
    }
}
