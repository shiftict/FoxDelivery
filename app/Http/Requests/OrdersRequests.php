<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrdersRequests extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * created method
     *
     * @return bool
     */
    public function created()
    {
        return [
            // only create user and store data
            'name' => 'required|max:255',
            'description' => 'nullable',
            'phone'   => 'required|min:8|max:10',
            'type_order'   => 'required|in:1,0',
            'lat_from' => 'required|max:255',
            'long_from' => 'required|max:255',
            'lat_to' => 'required|max:255',
            'long_to' => 'required|max:255',
            'date_from'     => 'required',
            'date_to'     => 'required',
            'time_from'     => 'required',
            'time_to'     => 'required',
            'delivery_id'       => 'exists:deliveries,id|nullable',
            'home' => 'required|max:255',
            'sabil' => 'required|max:255',
            'street' => 'required|max:255',
            'block' => 'required|max:255',
        ];
    }

    /**
     * update method
     *
     * @return bool
     */
    public function updated()
    {
        return [
            'name' => 'required|max:255',
            'description' => 'nullable',
            'phone'   => 'required|min:8|max:10',
            'type_order'   => 'required|in:1,0',
            'lat_from' => 'required|max:255',
            'long_from' => 'required|max:255',
            'lat_to' => 'required|max:255',
            'long_to' => 'required|max:255',
            'date_from'     => 'required',
            'date_to'     => 'required',
            'time_from'     => 'required',
            'time_to'     => 'required',
            'delivery_id'       => 'exists:deliveries,id|nullable',
            'home' => 'required|max:255',
            'sabil' => 'required|max:255',
            'street' => 'required|max:255',
            'block' => 'required|max:255',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // if post method only call created method else called update
        return $this->isMethod('post') ? $this->created() : $this->updated();
    }
}
