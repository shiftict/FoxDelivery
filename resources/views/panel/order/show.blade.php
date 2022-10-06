@extends('panel._layout')
@section('subTitle')
    @lang('navbar.showOrder')
@endsection

@push('style')

@endpush
@push('js')
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script async
            src="{{"https://maps.googleapis.com/maps/api/js?key=AIzaSyDQDC3VV5CGRaueUYpEEJ308KNx8zbG5t0&callback=initAutocomplete&libraries=places"}}"
    >
    </script>
    <!-- Async script executes immediately and must be after any DOM elements used in callback. -->
    <script type="text/javascript">
        function initAutocomplete() {
            const map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: -33.8688, lng: 151.2195 },
                zoom: 13,
                mapTypeId: "roadmap",
            });
            // Create the search box and link it to the UI element.
            const input = document.getElementById("pac-input");
            const inputs = document.getElementById("pac-inputs");
            const searchBox = new google.maps.places.SearchBox(input);
            const searchBoxs = new google.maps.places.SearchBox(inputs);

            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(inputs);
            // Bias the SearchBox results towards current map's viewport.
            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });

            let markers = [];

            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }

                // Clear out the old markers.
                markers.forEach((marker) => {
                    marker.setMap(null);
                });
                markers = [];

                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();

                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    const icon = {
                        url: place.icon,
                        size: new google.maps.Size(71, 71),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(17, 34),
                        scaledSize: new google.maps.Size(25, 25),
                    };

                    // Create a marker for each place.
                    markers.push(
                        new google.maps.Marker({
                            map,
                            icon,
                            title: place.name,
                            position: place.geometry.location,
                        })
                    );
                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
            searchBoxs.addListener("places_changed", () => {
                const places = searchBoxs.getPlaces();

                if (places.length == 0) {
                    return;
                }

                // Clear out the old markers.
                markers.forEach((marker) => {
                    marker.setMap(null);
                });
                markers = [];

                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();

                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    const icon = {
                        url: place.icon,
                        size: new google.maps.Size(71, 71),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(17, 34),
                        scaledSize: new google.maps.Size(25, 25),
                    };

                    // Create a marker for each place.
                    markers.push(
                        new google.maps.Marker({
                            map,
                            icon,
                            title: place.name,
                            position: place.geometry.location,
                        })
                    );
                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        }
    </script>
@endpush
@section('pageTitle')
    <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
    <span class="text-muted font-weight-bold mr-4">@lang('navbar.showOrder')</span>
    {{--<a href="#" class="btn btn-light-warning font-weight-bolder btn-sm">Add New</a>--}}
@endsection
@section('content')
    <!--begin::Row-->
    <div class="row">
        <div class="col-md-12">
            <!--begin::Card-->
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header">
                    <h3 class="card-title">
                        <span class="fas fa-shopping-cart icon-lg"></span> &nbsp&nbsp
                        {!! $message !!} &nbsp;&nbsp;
                        @if($order->delivery_id != NULL)
                            <span class="label label-inline label-light-success font-weight-bold">
                                <i class="fas fa-car-side" style="color:#000"></i> &nbsp;
                                {{$order->delivery->name}}
                            </span>
                        @endif
                    </h3>

                    @permission('report_read')
                    @if($order->vendor_id)
                        <h3 class="card-title">
                            <a class="btn btn-primary font-weight-bold mr-2" href="{{ route("order.printPdf", $order->id) }}">@lang('order.printOrder') <span class="far fa-file-pdf icon-lg"></span></a>
                        </h3>
                    @endif
                    @endpermission
                </div>
                <!--begin::Form-->
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label>@lang('order.from_address')
                                    <span class="text-danger">*</span></label>

                                <input disabled id="pac-input" autocapitalize="off" autocomplete="off" value="{{$order->from_address}}" type="text" name="from_address" class="form-control @error('from_address') is-invalid @enderror" placeholder="@lang('order.from_addressDescription')" />

                            </div>
                            <div class="col-lg-4">
                                <label>@lang('order.to_address')
                                    <span class="text-danger">*</span></label>

                                <input disabled id="pac-inputs" autocapitalize="off" autocomplete="off" value="{{$order->to_address}}" type="text" name="to_address" class="form-control @error('to_address') is-invalid @enderror" placeholder="@lang('order.to_addressDescription')" />

                            </div>
                            <div class="col-lg-4 mb-5">
                                <label>@lang('order.name')
                                    <span class="text-danger">*</span></label>
                                <input disabled id="pac-input" autocapitalize="off" autocomplete="off" value="{{$order->name}}" type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="@lang('order.nameOfCustomer')" />                                @if($errors->has('to_address'))
                                    <span class="text-danger">@lang('order.nameOfCustomer')</span>

                                @else
                                    <span class="form-text text-muted"></span>
                                @endif
                            </div>
                            <div class="col-lg-2">
                                <label>@lang('order.phone')
                                    <span class="text-danger">*</span></label>
                                <div class="input-group ">
                                    <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <span class="svg-icon svg-icon-md"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo1\dist/../src/media/svg/icons\Devices\Phone.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                        <rect x="0" y="0" width="24" height="24"/>
                                                                        <path d="M7.13888889,4 L7.13888889,19 L16.8611111,19 L16.8611111,4 L7.13888889,4 Z M7.83333333,1 L16.1666667,1 C17.5729473,1 18.25,1.98121694 18.25,3.5 L18.25,20.5 C18.25,22.0187831 17.5729473,23 16.1666667,23 L7.83333333,23 C6.42705272,23 5.75,22.0187831 5.75,20.5 L5.75,3.5 C5.75,1.98121694 6.42705272,1 7.83333333,1 Z" fill="#000000" fill-rule="nonzero"/>
                                                                        <polygon fill="#000000" opacity="0.3" points="7 4 7 19 17 19 17 4"/>
                                                                        <circle fill="#000000" cx="12" cy="21" r="1"/>
                                                                    </g>
                                                                </svg><!--end::Svg Icon-->
                                                        </span>
                                                    </span>
                                    </div>
                                    <input disabled autocomplete="off" value="{{$order->phone}}" type="number" class="form-control @error('phone') is-invalid @enderror" name="phone" placeholder="@lang('order.phonePlaceholder')" />
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <label for="exampleSelect1">@lang('order.home')
                                    <span class="text-danger">*</span></label>
                                <div class="input-daterange input-group @error('startL_order') is-invalid @enderror" id="kt_datepicker_6">
                                    <input disabled autocomplete="off" value="{{$order->home}}" name="date" type="text" class="form-control @error('date') is-invalid @enderror" />
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <label for="exampleSelect1">@lang('order.sabil')
                                    <span class="text-danger">*</span></label>
                                <div class="input-daterange input-group @error('startL_order') is-invalid @enderror" id="kt_datepicker_6">
                                    <input disabled autocomplete="off" value="{{$order->sabil}}" name="date" type="text" class="form-control @error('date') is-invalid @enderror" />
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <label for="exampleSelect1">@lang('order.street')
                                    <span class="text-danger">*</span></label>
                                <div class="input-daterange input-group @error('startL_order') is-invalid @enderror" id="kt_datepicker_6">
                                    <input disabled autocomplete="off" value="{{$order->street}}" name="date" type="text" class="form-control @error('date') is-invalid @enderror" />
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <label for="exampleSelect1">@lang('order.block')
                                    <span class="text-danger">*</span></label>
                                <div class="input-daterange input-group @error('startL_order') is-invalid @enderror" id="kt_datepicker_6">
                                    <input disabled autocomplete="off" value="{{$order->block}}" name="date" type="text" class="form-control @error('date') is-invalid @enderror" />
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <label for="exampleSelect1">@lang('order.typeOfOrder')
                                    <span class="text-danger">*</span></label>
                                <select disabled @change="onChangePackage($event)" name="type_order" class="form-control @error('type_order') is-invalid @enderror" id="package">
                                    <option value="">--</option>
                                    <option {{$order->type_order == 1 ? 'selected' : ''}} value="1">@lang('order.twoWay')</option>
                                    <option {{$order->type_order == 0 ? 'selected' : ''}} value="0">@lang('order.oneWay')</option>
                                </select>
                            </div>
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label for="exampleSelect1">@lang('order.description')</label>
                                <textarea disabled autocomplete="off" name="description" class="form-control @error('description') is-invalid @enderror" />{{$order->description}}</textarea>
                            </div>
                            <div class="col-lg-4 mt-5">
                                <label for="exampleSelect1">@lang('order.date_from')
                                    <span class="text-danger">*</span></label>
                                <div class="input-daterange input-group @error('start') is-invalid @enderror">
                                    <input autocomplete="off" disabled type="text" value="{{\Carbon\Carbon::parse($order->date_from)->format('Y-m-d')}}" class="form-control"/>
                                    <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="la la-ellipsis-h"></i>
                                                                    </span>
                                    </div>
                                    <input disabled value="{{$order->time_from}}" autocomplete="off" type="text" class="form-control"/>
                                </div>

                            </div>
                            <div class="col-lg-4 mt-5">
                                <label for="exampleSelect1">@lang('order.date_to')
                                    <span class="text-danger">*</span></label>
                                <div class="input-daterange input-group @error('start') is-invalid @enderror">
                                    <input disabled value="{{\Carbon\Carbon::parse($order->date_from)->format('Y-m-d')}}" autocomplete="off" type="text" class="form-control"/>
                                    <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="la la-ellipsis-h"></i>
                                                                    </span>
                                    </div>
                                    <input disabled value="{{$order->time_to}}" autocomplete="off" type="text" class="form-control"/>
                                </div>
                            </div>
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            <div class="col-lg-2 mt-5">
                                <label for="exampleSelect1">@lang('order.totale_amount')
                                    <span class="text-danger">*</span></label>
                                <div class="input-daterange input-group @error('startL_order') is-invalid @enderror" id="kt_datepicker_6">
                                    <input disabled autocomplete="off" value="{{$order->totale_amount}}" name="date" type="text" class="form-control @error('date') is-invalid @enderror" />
                                </div>
                            </div>
                            <div class="col-lg-2 mt-5">
                                <label for="exampleSelect1">@lang('order.payment_method')
                                    <span class="text-danger">*</span></label>
                                <div class="input-daterange input-group @error('startL_order') is-invalid @enderror" id="kt_datepicker_6">
                                    <input disabled autocomplete="off" value="{{$order->payment_method == 0 ? 'Online' : 'Cash'}}" name="date" type="text" class="form-control @error('date') is-invalid @enderror" />
                                </div>
                            </div>
                            <div class="col-lg-2 mt-5">
                                <label for="exampleSelect1">@lang('order.items')
                                    <span class="text-danger">*</span></label>
                                <div class="input-daterange input-group @error('startL_order') is-invalid @enderror" id="kt_datepicker_6">
                                    <input disabled autocomplete="off" value="{{$order->items}}" name="date" type="text" class="form-control @error('date') is-invalid @enderror" />
                                </div>
                            </div>
                            <div class="col-lg-2 mt-5">
                                <label for="exampleSelect1">@lang('order.Order Reference')
                                    <span class="text-danger">*</span></label>
                                <div class="input-daterange input-group @error('startL_order') is-invalid @enderror" id="kt_datepicker_6">
                                    <input disabled autocomplete="off" value="{{$order->order_reference}}" name="date" type="text" class="form-control @error('date') is-invalid @enderror" />
                                </div>
                            </div>
                        </div>

                        <div id="map"></div>
                        <div id="maps"></div>
                    </div>
                <!--end::Form-->
            </div>
            <!--end::Card-->
        </div>
    </div>
@endsection
