<?php

namespace App\Http\Controllers\Api\V1\Drivers;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\LoginResource;
use App\Http\Resources\Api\V1\VendorsResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
class AuthController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return $this->errorResponse(null, null,ResponseAlias::HTTP_NOT_FOUND);
    }

    /**
     * check [in-out] drivers.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkShift(Request $request)
    {
        // validation
        $request->validate([
            'e_online' => ['required', 'in:1,0'],
        ]);

        // check user is valid
        $driver = $request->user();

        if(!$driver) {
            return $this->errorResponse(null, __('api.user_not_found'),ResponseAlias::HTTP_NOT_FOUND);
        }

        // update status
        $driver->is_online = $request->e_online;
        $driver->update();

        // create object driver
        $driver_object = LoginResource::make($driver);

        // return success message
        return $this->successResponse($driver_object,__('api.success_update_online'),ResponseAlias::HTTP_OK);
    }

    /**
     * check [in-out] drivers.
     *
     * @return \Illuminate\Http\Response
     */
    public function changeLocation(Request $request)
    {
        // validation
        $request->validate([
            's_lat' => ['required'],
            's_long' => ['required'],
        ]);

        // check user is valid
        $driver = $request->user();

        if(!$driver) {
            return $this->errorResponse(null, __('api.user_not_found'),ResponseAlias::HTTP_NOT_FOUND);
        }


        // update location driver
        $driver->last_lat_driver = $request->s_lat;
        $driver->last_long_driver = $request->s_long;
        $driver->save();
// return $driver;
        // create object driver
        $driver_object = LoginResource::make($driver);

        // return success message
        return $this->successResponse($driver_object,__('api.success_update_online'),ResponseAlias::HTTP_OK);
    }
    
    /**
     * login vendor.
     *
     * @return \Illuminate\Http\Response
     */
    public function login_vendor(Request $request)
    {
        //
        $request->validate([
            's_email' => ['required'],
            's_password' => ['required', 'min:6'],
        ]);
        
        
        $driver = User::query()
            ->where('email', $request->s_email)
            ->first();
        if (!$driver) {
            return $this->errorResponse(null, __('api.user_not_found'),ResponseAlias::HTTP_NOT_FOUND);
        }
        if (!Hash::check($request->s_password, $driver->password)) {
            return $this->errorResponse(null, __('api.login_wrong'),ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }
        
         
        //;
        if(!$driver->package_orders()->exists()) {
            return $this->errorResponse(null, __('api.user_not_found'),ResponseAlias::HTTP_NOT_FOUND);
        }
        if($driver->store->status == '0') {
            return $this->errorResponse(null, __('api.suspended_account'),ResponseAlias::HTTP_NOT_FOUND);
        }
        
        if($driver->store->package_order->number_of_order == 0) {
            return $this->errorResponse(null, __('api.ex_package'),ResponseAlias::HTTP_NOT_FOUND);
        }
        $driver->device_key = $request->header('X-device-key') ?? null;
        $driver->tokenfcm = $request->header('X-Client-PNS-Token') ?? null;
        $driver->accessToken = $driver->createToken($request->header('X-Client-PNS-Key') ? $request->header('X-Client-PNS-Key') : $driver->id . '_' . Carbon::now()->toDateTimeString())->plainTextToken;
        $driver->last_login = now();
        $driver->update();
        $driver_object = VendorsResource::make($driver);
        return $this->successResponse($driver_object,__('api.login_success'),ResponseAlias::HTTP_OK);
    }

    /**
     * login driver.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        //
        $request->validate([
            's_mobile' => ['required'],
            's_password' => ['required', 'min:6'],
        ]);
        $driver = User::query()
            ->where('mobile', $request->s_mobile)
            ->first();
        if (!$driver) {
            return $this->errorResponse(null, __('api.user_not_found'),ResponseAlias::HTTP_NOT_FOUND);
        }
        if (!Hash::check($request->s_password, $driver->password)) {
            return $this->errorResponse(null, __('api.login_wrong'),ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        if(!$driver->delivery()->exists()) {
            return $this->errorResponse(null, __('api.user_not_found'),ResponseAlias::HTTP_NOT_FOUND);
        }
        // return $driver->delivery->status;
        if($driver->delivery->status == '0') {
            return $this->errorResponse(null, __('api.suspended_account'),ResponseAlias::HTTP_NOT_FOUND);
        }
        $driver->device_key = $request->header('X-device-key') ?? null;
        $driver->tokenfcm = $request->header('X-Client-PNS-Token') ?? null;
        $driver->accessToken = $driver->createToken($request->header('X-Client-PNS-Key') ? $request->header('X-Client-PNS-Key') : $driver->id . '_' . Carbon::now()->toDateTimeString())->plainTextToken;
        $driver->last_login = now();
        $driver->update();
        $driver_object = LoginResource::make($driver);
        return $this->successResponse($driver_object,__('api.login_success'),ResponseAlias::HTTP_OK);
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
     * logout driver.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        // get object driver
        $driver = $request->user();
        $driver->is_online = '0';
        $driver->save();
        // remove token user and logout driver
        $driver->currentAccessToken()->delete();

        // return success message
        return $this->successResponse(null, __('api.logout_successfully'), ResponseAlias::HTTP_OK);
    }

    /**
     * update password.
     *
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            's_old_password' => ['required', 'min:6'],
            's_new_password' => ['required', 'min:6'],
            's_password_confirmation' => ['required', 'same:s_new_password', 'min:6'],
        ]);

        // get object driver
        $driver = $request->user();
        if ((Hash::check($request->s_old_password, $driver->password))) {
            // update password
            $driver->update(['password' => Hash::make($request->s_new_password)]);
            // return success message
            return $this->successResponse(null, __('api.success_update_password'), ResponseAlias::HTTP_OK);
        }

        // return field message
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
