<?php

namespace App\Http\Resources\Vendor;

use App\Http\Resources\Packages\PackagesResource;
use App\Http\Resources\TimeDelivery\TimeDeliveryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nameAr' => $this->name,
            'nameEn' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'lat' => $this->lat,
            'long' => $this->long,
            'city' => $this->city,
            'package' => $this->package,
            'status' => $this->status,
            'employee' => $this->employee_id,
            'home' => $this->home,
            'block' => $this->block,
            'sabil' => $this->sabil,
            'street' => $this->street,
            'citys' => $this->citys,
//            'package' => $this->get_list_days->number_of_order == '' ? '1' : '0',
//            'timeDelivery' => TimeDeliveryResource::collection($this->timeDelivery),
//            'packages' => new PackagesResource($this->get_list_days),
        ];
    }
}
