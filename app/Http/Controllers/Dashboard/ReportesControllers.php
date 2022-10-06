<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\Vendor;
use App\Models\sliderApp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Http;
use Auth;
use UploadImage;

// exls
use App\Exports\{OrdersExport, DeliveriesExport, VendorsExport, VendorCustomExport};
use Maatwebsite\Excel\Facades\Excel;

class ReportesControllers extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:report_read'])
        ->only(['orderPdf','orderReports','ordersDatatable','deliveryPdf','deliveryReports','deliveryDatatable','vendorsPdf','vendorsReports','vendorsDatatable', 'vendorsCustomReports']);
    }
    
    protected $path_image = 'public/image/apk';
    
    // order reports
    public function orderPdf ($id) {
        $order = Order::where('id', $id)
            ->firstOrFail();
        view()->share(['data' => $order]);
        $pdf = Pdf::loadView('panel.reportes.orders.pdf', ['student' => $order]);
        $pdf->SetProtection(['copy', 'print'], '', 'pass');
        return $pdf->stream(rand(1111,99999).$order->name.'.pdf');
    }
    
    public function orderPdfFilter (Request $request) {
        $data = $request->all();
        $s_name = $request->get('s_name');
        $s_status = $request->get('b_enabled');
        $s_date_from = $request->get('date_from');
        $s_date_to = $request->get('date_to');
        $v = $request->get('vendor');
        $d = $request->get('driver');
        $order = Order::when($s_name, function ($q) use ($s_name) {
            return $q->where('name', 'LIKE', '%' . $s_name . '%');
        })
        ->when($s_status, function ($q) use ($s_status) {
            return $q->where('order_status', 'LIKE', '%' . $s_status . '%');
        })
        ->when($s_date_from, function ($q) use ($s_date_from) {
            return $q->where('date_from', '>', $s_date_from);
        })
        ->when($s_date_to, function ($q) use ($s_date_to) {
            return $q->where('date_to', '<', $s_date_to);
        })
        ->when($v, function ($q) use ($v) {
            return $q->where('vendor_id', $v);
        })
        ->when($d, function ($q) use ($d) {
            return $q->where('delivery_id', $d);
        })
        ->get();
        // return $order;
        view()->share(['data' => $order]);
        $pdf = Pdf::loadView('panel.reportes.orders.pdf-filter', ['student' => $order]);
        $pdf->SetProtection(['copy', 'print'], '', 'pass');
        return $pdf->stream(rand(11111,99999).'.pdf');
    }

    public function orderReports() {
        return view('panel.reportes.orders.reportes');
    }

    public function ordersDatatable(Request $request) {
        //
        if(Auth::user()->hasRole('vendor')) {
        $data = $request->all();
        $s_name = $request->get('s_name');
        $s_mobile_number = $request->get('s_mobile_number');
        $s_email = $request->get('s_email');
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');
        $v = $request->get('vendor');
        $d = $request->get('driver');
        
        $b_enabled = $request->get("b_enabled");

        $objects = Order::where('created_by', Auth::id())->whereNull('deleted_at');

        if(isset($d))
            $objects = $objects->where('delivery_id', $d);
            
        if(isset($v))
            $objects = $objects->where('vendor_id', $v);
            
        if(isset($s_name) && strlen($s_name) > 0)
            $objects = $objects->where('name', 'LIKE', '%'.$s_name.'%');
            
        if(isset($b_enabled))
            $objects = $objects->where('order_status', $b_enabled);

        if(isset($date_from) && strlen($date_from) > 0)
            $objects = $objects->whereDate('date', '>=', $date_from);

        if(isset($date_to) && strlen($date_to) > 0)
            $objects = $objects->whereDate('date', '<=', $date_to);

        if(isset($date_to) && strlen($date_to) > 0 && isset($date_from) && strlen($date_from) > 0)
            $objects = $objects->whereDate('date', '>=', $date_from)->where('date', '<=', $date_to);

        return DataTables::of($objects)
            ->addColumn('id', function ($object) {
                return $object->id;
            })
            ->addColumn('name', function ($object) {
                return $object->name;
            })
            ->addColumn('from', function ($object) {
                $googleMap = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $object->lat_from . ',' . $object->long_from . '&key=' . env('MAP_KEY');
                $response = Http::get($googleMap);
                $formatted_address = $response->json()['results'][0]['formatted_address'];
                return $formatted_address;
            })
            ->addColumn('to', function ($object) {
                $googleMap = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $object->lat_to . ',' . $object->long_to . '&key=' . env('MAP_KEY');
                $response = Http::get($googleMap);
                $formatted_address = $response->json()['results'][0]['formatted_address'];
                return $formatted_address;
            })
            ->addColumn('phone', function ($object) {
                return $object->phone;
            })
            ->addColumn('vendor', function ($object) {
                return $object->vendor()->exists() ? $object->vendor->name : '--';
            })
            ->addColumn('actions', function ($object) {
                $title = __('table.print');
                return '
				<a href="'.route("order.printPdf", $object->id).'" class="btn btn-sm btn-default btn-text-primary btn-hover-primary btn-icon" title="'.$title.'">

                    <span class="svg-icon svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo1\dist/../src/media/svg/icons\Devices\Printer.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"/>
                                <path d="M16,17 L16,21 C16,21.5522847 15.5522847,22 15,22 L9,22 C8.44771525,22 8,21.5522847 8,21 L8,17 L5,17 C3.8954305,17 3,16.1045695 3,15 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,15 C21,16.1045695 20.1045695,17 19,17 L16,17 Z M17.5,11 C18.3284271,11 19,10.3284271 19,9.5 C19,8.67157288 18.3284271,8 17.5,8 C16.6715729,8 16,8.67157288 16,9.5 C16,10.3284271 16.6715729,11 17.5,11 Z M10,14 L10,20 L14,20 L14,14 L10,14 Z" fill="#000000"/>
                                <rect fill="#000000" opacity="0.3" x="8" y="2" width="8" height="2" rx="1"/>
                            </g>
                        </svg><!--end::Svg Icon-->
                    </span>
			</a>';
            })
            ->addColumn('dt_creation_date', function ($object) {
                return Carbon::parse($object->created_at)->toDateString();
            })
            ->addIndexColumn()
            ->rawColumns(['actions', 'b_enabled'])
            ->make(true);
        } else {
        $data = $request->all();
        $s_name = $request->get('s_name');
        $s_mobile_number = $request->get('s_mobile_number');
        $s_email = $request->get('s_email');
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');

        $v = $request->get('vendor');
        $d = $request->get('driver');
        
        $b_enabled = $request->get("b_enabled");

        $objects = Order::whereNull('deleted_at');

        if(isset($d))
            $objects = $objects->where('delivery_id', $d);
            
        if(isset($v))
            $objects = $objects->where('vendor_id', $v);
            
        if(isset($s_name) && strlen($s_name) > 0)
            $objects = $objects->where('name', 'LIKE', '%'.$s_name.'%');

        if(isset($b_enabled) && $b_enabled == 5)
            $objects = $objects->whereIn('order_status', ['0', '1', '2', '3', '4']);

        if(isset($b_enabled) && $b_enabled != 5)
            $objects = $objects->where('order_status', $b_enabled);

        if(isset($date_from) && strlen($date_from) > 0)
            $objects = $objects->whereDate('date', '>=', $date_from);

        if(isset($date_to) && strlen($date_to) > 0)
            $objects = $objects->whereDate('date', '<=', $date_to);

        if(isset($date_to) && strlen($date_to) > 0 && isset($date_from) && strlen($date_from) > 0)
            $objects = $objects->whereDate('date', '>=', $date_from)->where('date', '<=', $date_to);

        return DataTables::of($objects)
            ->addColumn('id', function ($object) {
                return $object->id;
            })
            ->addColumn('name', function ($object) {
                return $object->name;
            })
            ->addColumn('from', function ($object) {
                return $object->from_address;
            })
            ->addColumn('to', function ($object) {
                return $object->to_address;
            })
            ->addColumn('phone', function ($object) {
                return $object->phone;
            })
            ->addColumn('vendor', function ($object) {
                return $object->vendor()->exists() ? $object->vendor->name : '--';
            })
            ->addColumn('actions', function ($object) {
                $title = __('table.print');
                return '
				<a href="'.route("order.printPdf", $object->id).'" class="btn btn-sm btn-default btn-text-primary btn-hover-primary btn-icon" title="'.$title.'">

                    <span class="svg-icon svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo1\dist/../src/media/svg/icons\Devices\Printer.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"/>
                                <path d="M16,17 L16,21 C16,21.5522847 15.5522847,22 15,22 L9,22 C8.44771525,22 8,21.5522847 8,21 L8,17 L5,17 C3.8954305,17 3,16.1045695 3,15 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,15 C21,16.1045695 20.1045695,17 19,17 L16,17 Z M17.5,11 C18.3284271,11 19,10.3284271 19,9.5 C19,8.67157288 18.3284271,8 17.5,8 C16.6715729,8 16,8.67157288 16,9.5 C16,10.3284271 16.6715729,11 17.5,11 Z M10,14 L10,20 L14,20 L14,14 L10,14 Z" fill="#000000"/>
                                <rect fill="#000000" opacity="0.3" x="8" y="2" width="8" height="2" rx="1"/>
                            </g>
                        </svg><!--end::Svg Icon-->
                    </span>
			</a>';
            })
            ->addColumn('dt_creation_date', function ($object) {
                return Carbon::parse($object->created_at)->toDateString();
            })
            ->addIndexColumn()
            ->rawColumns(['actions', 'b_enabled'])
            ->make(true);
        }
    }

    // Delivery reports
    public function deliveryPdf ($id) {
        $order = Delivery::where('id', $id)
            ->firstOrFail();
        view()->share(['data' => $order]);
        $pdf = Pdf::loadView('panel.reportes.drivers.pdf', ['student' => $order]);
        $pdf->SetProtection(['copy', 'print'], '', 'pass');
        return $pdf->stream(rand(1111,99999).$order->name.'.pdf');
    }
    
    public function deliveryPdfFilter (Request $request) {
         $data = $request->all();
        $s_name = $request->get('s_name');
        $s_mobile_number = $request->get('s_mobile_number');
        $s_email = $request->get('s_email');

        $s_status = $request->get("b_enabled");
        
        
        $order = Delivery::when($s_name, function ($q) use ($s_name) {
            return $q->where('name->' . app()->getLocale(), 'LIKE', '%'.$s_name.'%');
        })
        ->when($s_status, function ($q) use ($s_status) {
            return $q->where('status', $s_status == 2 ? '0' : '1');
        })
        ->when($s_email, function ($q) use ($s_email) {
            return $q->where('email', 'LIKE', '%'.$s_email.'%');
        })
        ->when($s_mobile_number, function ($q) use ($s_mobile_number) {
            return $q->where('phone', 'LIKE', '%'.$s_mobile_number.'%');
        })
        ->get();
        // return $s_status;
        view()->share(['data' => $order]);
        $pdf = Pdf::loadView('panel.reportes.drivers.pdf-filter', ['student' => $order]);
        $pdf->SetProtection(['copy', 'print'], '', 'pass');
        return $pdf->stream(rand(11111,99999).'.pdf');
    }

    public function deliveryReports() {
        return view('panel.reportes.drivers.reportes');
    }

    public function deliveryDatatable(Request $request) {
        //
            $data = $request->all();
        $s_name = $request->get('s_name');
        $s_mobile_number = $request->get('s_mobile_number');
        $s_email = $request->get('s_email');
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');

        $b_enabled = $request->get("b_enabled");

        $objects = Delivery::whereNull('deleted_at');

        if(isset($s_name) && strlen($s_name) > 0)
            $objects = $objects->where('name', 'LIKE', '%'.$s_name.'%');

        if(isset($s_mobile_number) && strlen($s_mobile_number) > 0)
            $objects = $objects->where('phone', 'LIKE', '%' . $s_mobile_number . '%');

        if(isset($s_email) && strlen($s_email) > 0)
            $objects = $objects->where('email', 'LIKE', '%' . $s_email . '%');

        if(isset($b_enabled) && $b_enabled == 1)
            $objects = $objects->where('status', $b_enabled);

        if(isset($b_enabled) && $b_enabled == 2)
            $objects = $objects->where('status', 0);

        if(isset($date_to) && strlen($date_to) > 0)
            $objects = $objects->whereDate('created_at', '<=', $date_to);

        if(isset($date_to) && strlen($date_to) > 0 && isset($date_from) && strlen($date_from) > 0)
            $objects = $objects->whereDate('created_at', '>=', $date_from)->where('date', '<=', $date_to);

        return DataTables::of($objects)
            ->addColumn('id', function ($object) {
                return $object->id;
            })
            ->addColumn('name', function ($object) {
                return $object->name;
            })
            ->addColumn('phone', function ($object) {
                return $object->phone;
            })
            ->addColumn('email', function ($object) {
                return $object->email;
            })
            ->addColumn('methodShipping', function ($object) {
                return $object->methodShipping->getTranslation('name', app()->getLocale())??'--';
            })
            ->addColumn('actions', function ($object) {
                $title = __('table.print');
                return '
				<a href="'.route("deliveries.printPdf", $object->id).'" class="btn btn-sm btn-default btn-text-primary btn-hover-primary btn-icon" title="'.$title.'">

                    <span class="svg-icon svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo1\dist/../src/media/svg/icons\Devices\Printer.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"/>
                                <path d="M16,17 L16,21 C16,21.5522847 15.5522847,22 15,22 L9,22 C8.44771525,22 8,21.5522847 8,21 L8,17 L5,17 C3.8954305,17 3,16.1045695 3,15 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,15 C21,16.1045695 20.1045695,17 19,17 L16,17 Z M17.5,11 C18.3284271,11 19,10.3284271 19,9.5 C19,8.67157288 18.3284271,8 17.5,8 C16.6715729,8 16,8.67157288 16,9.5 C16,10.3284271 16.6715729,11 17.5,11 Z M10,14 L10,20 L14,20 L14,14 L10,14 Z" fill="#000000"/>
                                <rect fill="#000000" opacity="0.3" x="8" y="2" width="8" height="2" rx="1"/>
                            </g>
                        </svg><!--end::Svg Icon-->
                    </span>
			</a>';
            })
            ->addColumn('dt_creation_date', function ($object) {
                return Carbon::parse($object->created_at)->toDateString();
            })
            ->addIndexColumn()
            ->rawColumns(['actions', 'b_enabled'])
            ->make(true);
    }

    // vendors reports
    public function vendorsPdf ($id) {
        $vedor = Vendor::where('id', $id)
            ->with(['pacakges'])
            ->firstOrFail();
        view()->share(['data' => $vedor]);
        $pdf = Pdf::loadView('panel.reportes.vendors.pdf', ['student' => $vedor]);
        $pdf->SetProtection(['copy', 'print'], '', 'pass');
        return $pdf->stream(rand(1111,99999).$vedor->name.'.pdf');
    }
    
    public function vendorsPdfFilter (Request $request) {
         $data = $request->all();
        $s_name = $request->get('s_name');
        $s_mobile_number = $request->get('s_mobile_number');
        $s_email = $request->get('s_email');

        $s_status = $request->get("b_enabled");
        
        
        $order = Vendor::when($s_name, function ($q) use ($s_name) {
            return $q->where('name->' . app()->getLocale(), 'LIKE', '%'.$s_name.'%');
        })
        ->when($s_status, function ($q) use ($s_status) {
            return $q->where('status', $s_status == 2 ? '0' : '1');
        })
        ->when($s_email, function ($q) use ($s_email) {
            return $q->where('email', 'LIKE', '%'.$s_email.'%');
        })
        ->when($s_mobile_number, function ($q) use ($s_mobile_number) {
            return $q->where('phone', 'LIKE', '%'.$s_mobile_number.'%');
        })
        ->get();
        // return $s_status;
        view()->share(['data' => $order]);
        $pdf = Pdf::loadView('panel.reportes.vendors.pdf-filter', ['student' => $order]);
        $pdf->SetProtection(['copy', 'print'], '', 'pass');
        return $pdf->stream(rand(11111,99999).'.pdf');
    }

    public function vendorsReports() {
        return view('panel.reportes.vendors.reportes');
    }

    public function vendorsDatatable(Request $request) {
        //
        if(Auth::user()->hasRole('superadministrator')) {
            $data = $request->all();
        $s_name = $request->get('s_name');
        $s_mobile_number = $request->get('s_mobile_number');
        $s_email = $request->get('s_email');

        $b_enabled = $request->get("b_enabled");

        $objects = Vendor::with(['get_list_days'])->whereNull('deleted_at');

        if(isset($s_name) && strlen($s_name) > 0)
            $objects = $objects->where('name->' . app()->getLocale(), 'LIKE', '%'.$s_name.'%');
        if(isset($s_mobile_number) && strlen($s_mobile_number) > 0)
            $objects = $objects->where('phone', 'LIKE', '%'.$s_mobile_number.'%');
        if(isset($s_email) && strlen($s_email) > 0)
            $objects = $objects->where('email', 'LIKE', '%'.$s_email.'%');

        if(isset($b_enabled) && $b_enabled == 1)
            $objects = $objects->where('status', $b_enabled);

        if(isset($b_enabled) && $b_enabled == 2)
            $objects = $objects->where('status', '0');

        return DataTables::of($objects)
            ->addColumn('id', function ($object) {
                return $object->id;
            })
            ->addColumn('name', function ($object) {
                return $object->getTranslation('name', app()->getLocale())??'--';
            })
            ->addColumn('email', function ($object) {
                return $object->email;
            })
            ->addColumn('user', function ($object) {
                return $object->user->name;
            })
            ->addColumn('address', function ($object) {
                return $object->city;
            })

            ->addColumn('actions', function ($object) {
                $title = __('table.print');
                return '<a href="'.route("vendors.printPdf", $object->id).'" class="btn btn-sm btn-default btn-text-primary btn-hover-primary btn-icon" title="'.$title.'">

                <span class="svg-icon svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo1\dist/../src/media/svg/icons\Devices\Printer.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24"/>
                            <path d="M16,17 L16,21 C16,21.5522847 15.5522847,22 15,22 L9,22 C8.44771525,22 8,21.5522847 8,21 L8,17 L5,17 C3.8954305,17 3,16.1045695 3,15 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,15 C21,16.1045695 20.1045695,17 19,17 L16,17 Z M17.5,11 C18.3284271,11 19,10.3284271 19,9.5 C19,8.67157288 18.3284271,8 17.5,8 C16.6715729,8 16,8.67157288 16,9.5 C16,10.3284271 16.6715729,11 17.5,11 Z M10,14 L10,20 L14,20 L14,14 L10,14 Z" fill="#000000"/>
                            <rect fill="#000000" opacity="0.3" x="8" y="2" width="8" height="2" rx="1"/>
                        </g>
                    </svg><!--end::Svg Icon-->
                </span>
        </a>';
            })
            ->addColumn('dt_creation_date', function ($object) {
                return Carbon::parse($object->created_at)->toDateString();
            })
            ->addIndexColumn()
            ->rawColumns(['actions', 'b_enabled'])
            ->make(true);
        }
    }
    
    public function orderPdfEmployee ($id) {
        $order = Order::where('id', $id)
            ->firstOrFail();
        view()->share(['data' => $order]);
        $pdf = Pdf::loadView('panel.reportes.orders.pdf', ['student' => $order]);
        $pdf->SetProtection(['copy', 'print'], '', 'pass');
        return $pdf->stream(rand(1111,99999).$order->name.'.pdf');
    }
    
    public function apkUploade(Request $request) {
        $this->validate($request, [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|',
        ]);
        // 1- check request have file
        if ($request->hasFile('file')){
            // 2- call uploade function
            $path = UploadImage::upload_image($request->file, $this->path_image);
            // 3- format json
            $data = json_decode(json_encode($path), true);
            // 4- extension file
            $ext  = $data['original']['ext'];
            // 5- name file
            $name = $data['original']['name'];
            // 6- push file data to array
            $array = ['ext' => $ext, 'name' => $name];
            // 7- push to session file
            sliderApp::create(['name' => $name, 'ext' => $ext]);
        }
    }
    
    public function apkUploadePage() {
        $attachment = sliderApp::get();
        return view('panel.sliderApp.index', compact('attachment'));
    }
    
    public function apkDeleteImage($id) {
        
        $attachments = sliderApp::find($id);
        if($attachments) {
            $attachments->delete();
            toastr()->success(__('alert.successUpdate'));
            return redirect()->route('apk.attacheds');
        }
        toastr()->info(__('alert.errorMessage'));
        return redirect()->route('apk.attacheds');
    }
    
    public function vendorsCustomReports() {
        $vendors = Vendor::get();
        return view('panel.reportes.vendors.custom-reportes', compact('vendors'));
    }
    
    /*
    * xls
    */
    public function orderXLSFilter (Request $request) {
        $data = $request->all();
        $s_name = $request->get('s_name');
        $s_status = $request->get('b_enabled');
        $s_date_from = $request->get('date_from');
        $s_date_to = $request->get('date_to');
        $v = $request->get('vendor');
        $d = $request->get('driver');
        $order = Order::when($s_name, function ($q) use ($s_name) {
            return $q->where('name', 'LIKE', '%' . $s_name . '%');
        })
        ->when($s_status, function ($q) use ($s_status) {
            return $q->where('order_status', 'LIKE', '%' . $s_status . '%');
        })
        ->when($s_date_from, function ($q) use ($s_date_from) {
            return $q->where('date_from', '>', $s_date_from);
        })
        ->when($s_date_to, function ($q) use ($s_date_to) {
            return $q->where('date_to', '<', $s_date_to);
        })
        ->when($v, function ($q) use ($v) {
            return $q->where('vendor_id', $v);
        })
        ->when($d, function ($q) use ($d) {
            return $q->where('delivery_id', $d);
        })
        ->get();
        // return $order;
        // view()->share(['data' => $order]);
        // $pdf = Pdf::loadView('panel.reportes.orders.pdf-filter', ['student' => $order]);
        // $pdf->SetProtection(['copy', 'print'], '', 'pass');
        // return $pdf->stream(rand(11111,99999).'.pdf');
        return Excel::download(new OrdersExport($order), rand(111,9999).'_OrderExport.xlsx');
    }
    
    public function vendorsXLSFilter (Request $request) {
         $data = $request->all();
        $s_name = $request->get('s_name');
        $s_mobile_number = $request->get('s_mobile_number');
        $s_email = $request->get('s_email');

        $s_status = $request->get("b_enabled");
        
        
        $order = Vendor::when($s_name, function ($q) use ($s_name) {
            return $q->where('name->' . app()->getLocale(), 'LIKE', '%'.$s_name.'%');
        })
        ->when($s_status, function ($q) use ($s_status) {
            return $q->where('status', $s_status == 2 ? '0' : '1');
        })
        ->when($s_email, function ($q) use ($s_email) {
            return $q->where('email', 'LIKE', '%'.$s_email.'%');
        })
        ->when($s_mobile_number, function ($q) use ($s_mobile_number) {
            return $q->where('phone', 'LIKE', '%'.$s_mobile_number.'%');
        })
        ->get();
        // return $s_status;
        return Excel::download(new VendorsExport($order), rand(111,9999).'_VendosExport.xlsx');
    }
    
    public function deliveryXLSFilter (Request $request) {
         $data = $request->all();
        $s_name = $request->get('s_name');
        $s_mobile_number = $request->get('s_mobile_number');
        $s_email = $request->get('s_email');

        $s_status = $request->get("b_enabled");
        
        
        $order = Delivery::when($s_name, function ($q) use ($s_name) {
            return $q->where('name->' . app()->getLocale(), 'LIKE', '%'.$s_name.'%');
        })
        ->when($s_status, function ($q) use ($s_status) {
            return $q->where('status', $s_status == 2 ? '0' : '1');
        })
        ->when($s_email, function ($q) use ($s_email) {
            return $q->where('email', 'LIKE', '%'.$s_email.'%');
        })
        ->when($s_mobile_number, function ($q) use ($s_mobile_number) {
            return $q->where('phone', 'LIKE', '%'.$s_mobile_number.'%');
        })
        ->get();
        // return $s_status;
        return Excel::download(new DeliveriesExport($order), rand(111,9999).'_DriversExport.xlsx');
    }
    
    public function vendorsCustomXLSFilter (Request $request) {
        
        // variable
        $email = $request->s_email;
        $id_vendor = $request->id_vendor;
        $code = $request->code;
        
        // validation
        if($request->filled($id_vendor) && $request->filled($email) && $request->filled($code)) {
            return redirect()->back();
        }
        
        // start query
        $vendors = Vendor::when($email , function ($q) use($email) {
                                $q->where('email', 'LIKE', '%'.$email.'%');
                            })
                            ->when($code , function ($q) use($code) {
                                $q->where('code', 'LIKE', '%'.$code.'%');
                            })
                            ->when($id_vendor , function ($q) use($id_vendor) {
                                $q->where('id', $id_vendor);
                            })
                            ->with(['orders.city', 'orders.delivery.methodShipping'])
                            ->withSum('orders', 'totale_amount')
                            ->get();
        
        if(count($vendors) > 0) {
            return Excel::download(new VendorCustomExport($vendors), '_vendor_report.xlsx');
        }
        
        return redirect()->back();
    }
}
