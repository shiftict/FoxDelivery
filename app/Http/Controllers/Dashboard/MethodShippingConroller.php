<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\MethodShipping;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Auth;

class MethodShippingConroller extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:method_shipping_read'])->only('index');
        $this->middleware(['permission:method_shipping_create'])->only('create');
        $this->middleware(['permission:method_shipping_delete'])->only('destroy');
        $this->middleware(['permission:method_shipping_update'])->only(['edit|status']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        //
        return view('panel.methodShipping.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('panel.methodShipping.create');
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
        $validator = $this->validate($request, [
            'name.ar' => 'required_if:name.en,==,null|max:255',
            'name.en' => 'required_if:name.ar,==,null|max:255',
            'price' => 'required|numeric|min:1',
            'status' => 'required|min:0|max:1'
        ]);
        $data = $request->only(['name', 'status', 'price']);
        $newMethods = MethodShipping::create($data);
        toastr()->success(__('alert.successCreated'), __('alert.success'));
        return redirect()->route('method_shipping.index');
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
        $methodShipping = MethodShipping::query()->find($id);
        if(!$methodShipping)
        {
            toastr()->error(__('alert.errorMessage'), __('alert.cancel'));
            return redirect()->route('method_shipping.index');
        }
        return view('panel.methodShipping.edit', compact('methodShipping'));
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
        $validator = $this->validate($request, [
            'name.ar' => 'required_if:name.en,==,null|max:255',
            'name.en' => 'required_if:name.ar,==,null|max:255',
            'price' => 'required|numeric|min:1',
            'status' => 'required|min:0|max:1'
        ]);
        $data = $request->only(['name', 'status', 'price']);
        $update = MethodShipping::find($id);
        if ($update) {
            $update->update($data);
        }
        toastr()->success(__('alert.successCreated'), __('alert.success'));
        return redirect()->route('method_shipping.index');
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
        $methodShipping = MethodShipping::find($id);
        if ($methodShipping) {
            $methodShipping->delete();
            return response()->json([
                'success' => TRUE,
                'message' => __('alert.successUpdate')
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => __('alert.successUpdate')
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request)
    {
        //
        if ($request->pk_i_id == NULL) {
            return response()->json([
                'success' => False,
                'message' => __('alert.errorMessage')
            ]);
        }
        $id = $request->pk_i_id;
        $update = MethodShipping::find($id);
        if ($update) {
            if ($update->status) {
                $update->update(['status' => '0']);
            } else {
                $update->update(['status' => '1']);
            }
            return response()->json([
                'success' => TRUE,
                'message' => __('alert.successUpdate')
            ]);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function datatable(Request $request)
    {
        //
        $data = $request->all();
        $s_name = $request->get('s_name');
        $s_mobile_number = $request->get('s_mobile_number');
        $s_email = $request->get('s_email');

        $b_enabled = $request->get("b_enabled");

        $objects = MethodShipping::whereNull('deleted_at');

        if(isset($s_name) && strlen($s_name) > 0)
            $objects = $objects->where('name->' . app()->getLocale(), 'LIKE', $s_name.'%');

        if(isset($b_enabled) && $b_enabled == 1)
            $objects = $objects->where('status', $b_enabled);

        if(isset($b_enabled) && $b_enabled == 2)
            $objects = $objects->where('status', '0');

        return DataTables::of($objects)
            ->addColumn('id', function ($object) {
//                return app()->getLocale();
                return $object->id;
            })
            ->addColumn('s_name', function ($object) {
//                return app()->getLocale();
                return $object->getTranslation('name', app()->getLocale())??'--';
            })
            ->addColumn('price', function ($object) {
//                return app()->getLocale();
                return $object->price;
            })
            ->addColumn('b_enabled', function ($object) {
                $is_enabled = '';
                $is_enabled .= '<div class="col-12">';
                $is_enabled .= '<span class="switch switch-sm switch-outline switch-icon switch-primary"><label>';
                if ($object->status == TRUE) {
                    $is_enabled .= '<input onclick="ch_st(' . $object->id . ',\'' . route('method_shipping.status') . '\',\'' . csrf_token() . '\')" type="checkbox" checked="checked" name="select" />';
                } else {
                    $is_enabled .= '<input onclick="ch_st(' . $object->id . ',\'' . route('method_shipping.status') . '\',\'' . csrf_token() . '\')" type="checkbox" name="select" />';
                }
                $is_enabled .= '<span></span></label>';
                $is_enabled .= '</span>';
                $is_enabled .= '</div>';
                return $is_enabled;
            })

            ->addColumn('actions', function ($object) {
                $action = '';
                if(Auth::user()->hasPermission('method_shipping_update')) {
                    $action .= '<div class="dropdown dropdown-inline">
                                <a href="javascript:;" class="btn btn-sm btn-default btn-text-primary btn-hover-primary btn-icon" data-toggle="dropdown">
                                    <span class="svg-icon svg-icon-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="svg-icon">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <path d="M7,3 L17,3 C19.209139,3 21,4.790861 21,7 C21,9.209139 19.209139,11 17,11 L7,11 C4.790861,11 3,9.209139 3,7 C3,4.790861 4.790861,3 7,3 Z M7,9 C8.1045695,9 9,8.1045695 9,7 C9,5.8954305 8.1045695,5 7,5 C5.8954305,5 5,5.8954305 5,7 C5,8.1045695 5.8954305,9 7,9 Z" fill="#000000"/>
                                                <path d="M7,13 L17,13 C19.209139,13 21,14.790861 21,17 C21,19.209139 19.209139,21 17,21 L7,21 C4.790861,21 3,19.209139 3,17 C3,14.790861 4.790861,13 7,13 Z M17,19 C18.1045695,19 19,18.1045695 19,17 C19,15.8954305 18.1045695,15 17,15 C15.8954305,15 15,15.8954305 15,17 C15,18.1045695 15.8954305,19 17,19 Z" fill="#000000" opacity="0.3"/>
                                            </g>
                                        </svg>
                                    </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                    <ul class="navi flex-column navi-hover py-2">
                                        <li class="navi-header font-weight-bolder text-uppercase font-size-xs text-primary pb-2">
                                            '.trans("table.action_title_tabe").':
                                        </li>
                                        <li class="navi-item">
                                            <a href="'.route("method_shipping.edit", $object->id).'" class="navi-link">
                                                <span class="navi-icon"><i class="la la-list-alt"></i></span>
                                                <span class="navi-text">'.trans("table.action_edit").'</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>';
                }
                if(Auth::user()->hasPermission('method_shipping_delete')) {
                    $action .= '
                            <a href="javascript:;" onclick="DeleteConfirm('.$object->id.',\''.route("method_shipping.destroy", $object->id).'\')" class="btn btn-sm btn-default btn-text-primary btn-hover-primary btn-icon" title="Delete">
                            <span class="svg-icon svg-icon-md">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"/>
                                        <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"/>
                                    </g>
                                </svg>
                            </span>
                        </a>';
                }
                return $action;
            })
            ->addColumn('dt_creation_date', function ($object) {
                return Carbon::parse($object->dt_created_date)->toDateString();
            })
            ->addIndexColumn()
            ->rawColumns(['actions', 'b_enabled'])
            ->make(true);
    }
}
