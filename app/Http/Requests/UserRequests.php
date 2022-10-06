<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequests extends FormRequest
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
            'email'       => 'required|email|max:50|unique:users,email',
            'lat' => 'required',
            'long' => 'required',
            'address' => 'required',
            'password' => 'required|min:6|max:255',
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
            // only update user and store data
            'name' => 'required|max:255',
            'email'       => 'required|email|max:50|unique:users,email,' . $this->user . ',id',
            'lat' => 'required',
            'long' => 'required',
            'address' => 'required',
            'password' => 'max:255|min:6|nullable',
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
