<?php

namespace App\Http\Resources\Delivery;

use Illuminate\Http\Resources\Json\JsonResource;

class Driversresource extends JsonResource
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
            'id' => $this->drivers->id,
            'name' => $this->drivers->name,
        ];
    }
}
