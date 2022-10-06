<?php

namespace App\Http\Controllers\Api\V1\Drivers;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\StatusResource;
use App\Models\StatusSystem;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class StatusController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // query get status
        $status_sys = StatusSystem::where('status', '1')->whereNotIn('id', [5, 6, 7])->get();

        // return data
        if ($status_sys->count() == 0 || $status_sys->count() < 0) {
            return $this->errorResponse(null, __('api.not_found_status'),ResponseAlias::HTTP_NOT_FOUND);
        }

        // success data
        $status_object = StatusResource::collection($status_sys);
        return $this->successListResponse($status_object,null,ResponseAlias::HTTP_OK);
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
