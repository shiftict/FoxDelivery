<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Exceptions\HttpResponseException;

class VendorRequests extends FormRequest
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
            'nameAr' => 'required_if:nameEn,==,null|max:255',
            'nameEn' => 'required_if:nameAr,==,null|max:255',
            'phone'   => 'required|min:8|max:10',
            'password' => 'required|min:6|max:20',
            'email'   => 'required|email|unique:users,email|max:50',
            'lat' => 'required|max:255',
            'long' => 'required|max:255',
            'package' => 'required|in:1,0',
            'status'  => 'required|in:1,0',
            // only create packages | package per hours
            'packageHours'    => 'required_if:package,==,1|array',
            'packageHours.*.start_shift'     => 'required_if:package,==,1',
            'packageHours.*.end_shift'       => 'required_if:package,==,1',
            'mainPackageHours.start'           => 'required_if:package,==,1',
            'mainPackageHours.end'             => 'after_or_equal:starts|nullable',
            'packageHours.option.*.drivers'       => 'required_if:package,==,1|exists:users,id',
            'mainPackageHours.pricing'        => 'required_if:package,==,1|max:255',
            // only create packages | package per order
            'packageOrder'    => 'required_if:package,==,0|array',
            'packageOrder.pricing'         => 'required_if:package,==,0|max:255',
            'packageOrder.stock'           => 'required_if:package,==,0|max:255',
            'packageOrder.startL_order'    => 'required_if:package,==,0',
//            'packageOrder.endL_order'      => 'required_if:packageOrder.startL_order,!=,null|after_or_equal:startL_order',
            'packageOrder.endL_order'      => 'after_or_equal:startL_order|nullable',
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
            'nameAr' => 'required_if:name.en,==,null|max:255',
            'nameEn' => 'required_if:name.ar,==,null|max:255',
            'phone'   => 'required|min:8|max:10',
            'lat' => 'required|max:255',
            'long' => 'required|max:255',
            'package' => 'required|in:1,0',
            'status'  => 'required|in:1,0',
            // only create packages | package per hours
            'packageHours'    => 'required_if:package,==,1|array',
            'packageHours.*.start_shift'     => 'required_if:package,==,1',
            'packageHours.*.end_shift'       => 'required_if:package,==,1',
            'mainPackageHours.start'           => 'required_if:package,==,1',
            'mainPackageHours.end'             => 'after_or_equal:starts|nullable',
            'packageHours.option.*.drivers'       => 'required_if:package,==,1|exists:users,id',
            'mainPackageHours.pricing'        => 'required_if:package,==,1|max:255',
            // only create packages | package per order
            'packageOrder'    => 'required_if:package,==,0|array',
            'packageOrder.pricing'         => 'required_if:package,==,0|max:255',
            'packageOrder.stock'           => 'required_if:package,==,0|max:255',
            'packageOrder.startL_order'    => 'required_if:package,==,0',
//            'packageOrder.endL_order'      => 'required_if:packageOrder.startL_order,!=,null|after_or_equal:startL_order',
            'packageOrder.endL_order'      => 'after_or_equal:startL_order|nullable',
        ];
    }

//    public function messages()
//    {
//        return [
//            'nameAr.required' => __('store.nameAr'),
//            'nameEn.required' => __('store.nameEn'),
//            'phone.required' => __('store.phone'),
//            'lat.required' => __('store.lat'),
//            'long.required' => __('store.long'),
//            'package.required' => __('store.package'),
//            'status.required' => __('store.status'),
//        ];
//    }

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

    // if have any file from store here is clear from session
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        // remove session file
        if (Session::get('file')){
            foreach (Session::get('file') as $key => $image) {
                if (file_exists(public_path('storage/image/store/' . $image['name']))) {
                    unlink(public_path('storage/image/store/' . $image['name']));
                }
            }
            Session::forget('file');
        }
        // pass message validation
//        parent::failedValidation($validator);
        throw new HttpResponseException(
            response()
                ->json(
                    [
                        'error' => [
                            'message' => $validator->errors(),
                            'status_code' => 422
                        ]
                    ],
                    422
                )
        );
    }
}
