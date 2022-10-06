<?php

namespace App\Http\Resources\TimeDelivery;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TimeDeliveryResource extends JsonResource
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
            'start_shift' => $this->time_from,
            'end_shift' => $this->time_to,
            'drivers' => $this->delivery_id,
        ];
    }
}
