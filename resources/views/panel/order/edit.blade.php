@extends('panel._layout')
@section('subTitle')
    @lang('navbar.editOrder')
@endsection

@push('style')
<style>
    label#phone-error {
        color: red;
    }
.pac-container {

    z-index : 200000;
}

#map-canvas {
	height: 75%;
	width: 75%;
}


</style>
@endpush
@push('js')
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script
            src="{{"https://maps.googleapis.com/maps/api/js?key=AIzaSyDQDC3VV5CGRaueUYpEEJ308KNx8zbG5t0&language=".app()->getLocale()."&libraries=places"}}"
            type="text/javascript">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
    <!-- Async script executes immediately and must be after any DOM elements used in callback. -->
    <script type="text/javascript">
        var initMap = function () {


            var map1 = function() {
                var mapOptions, map, marker, searchBox, city,
        		infoWindow = '';
                $("#lat_from").val('{!! $order->lat_from !!}');
                $("#long_from").val('{!! $order->long_from !!}');
                var latEl = document.querySelector( '#lat_from' );
        		var longEl = document.querySelector( '#long_from' );
                // init location

        		var addressEl = document.querySelector( '#map-search' );

        		var element = document.getElementById( 'map' );
        	    var city = document.querySelector( '.reg-input-city' );

        	mapOptions = {
        		// How far the maps zooms in.
        		zoom: 8,
        		// Current Lat and Long position of the pin/
        		@if(auth()->user()->store()->exists())
        		center: new google.maps.LatLng( {!! auth()->user()->store->lat !!}, {!! auth()->user()->store->long !!} ),
        		@else
                center: new google.maps.LatLng( 29.33744230597737, 48.022533337402336 ),
                @endif

        		disableDefaultUI: false, // Disables the controls like zoom control on the map if set to true
        		scrollWheel: true, // If set to false disables the scrolling on the map.
        		draggable: true, // If set to false , you cannot move the map around.
        		// mapTypeId: google.maps.MapTypeId.HYBRID, // If set to HYBRID its between sat and ROADMAP, Can be set to SATELLITE as well.
        		// maxZoom: 11, // Wont allow you to zoom more than this
        		// minZoom: 9  // Wont allow you to go more up.

        	};

        	/**
        	 * Creates the map using google function google.maps.Map() by passing the id of canvas and
        	 * mapOptions object that we just created above as its parameters.
        	 *
        	 */
        	// Create an object map with the constructor function Map()
        	map = new google.maps.Map( element, mapOptions ); // Till this like of code it loads up the map.

        	/**
        	 * Creates the marker on the map
        	 *
        	 */
        	marker = new google.maps.Marker({
        		position: mapOptions.center,
        		map: map,
        		// icon: 'http://pngimages.net/sites/default/files/google-maps-png-image-70164.png',
        		draggable: true
        	});

        	/**
        	 * Creates a search box
        	 */
        	searchBox = new google.maps.places.SearchBox( addressEl );

        	/**
        	 * When the place is changed on search box, it takes the marker to the searched location.
        	 */
        	google.maps.event.addListener( searchBox, 'places_changed', function () {
        		var places = searchBox.getPlaces(),
        			bounds = new google.maps.LatLngBounds(),
        			i, place, lat, long, resultArray,
        			addresss = places[0].formatted_address;
        			$('#exampleModalLabelfromf').text(addresss);
        			$('#exampleModalLabelfromHidden').val(addresss);
        			$('#exampleModalLabelfromf').text(addresss);

        		for( i = 0; place = places[i]; i++ ) {
        			bounds.extend( place.geometry.location );
        			marker.setPosition( place.geometry.location );  // Set marker position new.
        		}

        		map.fitBounds( bounds );  // Fit to the bound
        		map.setZoom( 15 ); // This function sets the zoom to 15, meaning zooms to level 15.
        		// console.log( map.getZoom() );

        		lat = marker.getPosition().lat();
        		long = marker.getPosition().lng();
        		latEl.value = lat;
        		longEl.value = long;

        		resultArray =  places[0].address_components;

        		// Get the city and set the city input value to the one selected
        		for( var i = 0; i < resultArray.length; i++ ) {
        			if ( resultArray[ i ].types[0] && 'administrative_area_level_2' === resultArray[ i ].types[0] ) {
        				citi = resultArray[ i ].long_name;

        				// console.log(city)
        				// city.value = citi;
        			}
        		}

        		// Closes the previous info window if it already exists
        		if ( infoWindow ) {
        			infoWindow.close();
        		}
        		/**
        		 * Creates the info Window at the top of the marker
        		 */
        		infoWindow = new google.maps.InfoWindow({
        			content: addresss
        		});

        		infoWindow.open( map, marker );
        	} );


        	/**
        	 * Finds the new position of the marker when the marker is dragged.
        	 */
        	google.maps.event.addListener( marker, "dragend", function ( event ) {
        		var lat, long, address, resultArray, citi;

        		console.log( 'i am dragged' );
        		lat = marker.getPosition().lat();
        		long = marker.getPosition().lng();

        		var geocoder = new google.maps.Geocoder();
        		geocoder.geocode( { latLng: marker.getPosition() }, function ( result, status ) {
        			if ( 'OK' === status ) {  // This line can also be written like if ( status == google.maps.GeocoderStatus.OK ) {
        				address = result[0].formatted_address;
        				resultArray =  result[0].address_components;
        				$('#exampleModalLabelfromf').text(address);
        			$('#exampleModalLabelfromHidden').val(address);
        			$('#exampleModalLabelfromf').text(address);

        				// Get the city and set the city input value to the one selected
        				for( var i = 0; i < resultArray.length; i++ ) {
        					if ( resultArray[ i ].types[0] && 'administrative_area_level_2' === resultArray[ i ].types[0] ) {
        						citi = resultArray[ i ].long_name;
        						console.log( citi );
        					}
        				}
        				addressEl.value = address;
        				latEl.value = lat;
        				longEl.value = long;
                        console.log(lat)
        			} else {
        				console.log( 'Geocode was not successful for the following reason: ' + status );
        			}

        			// Closes the previous info window if it already exists
        			if ( infoWindow ) {
        				infoWindow.close();
        			}

        			/**
        			 * Creates the info Window at the top of the marker
        			 */
        			infoWindow = new google.maps.InfoWindow({
        				content: address
        			});

        			infoWindow.open( map, marker );
        		} );
        	});
            }

            var map2 = function() {
                var mapOptions, map, marker, searchBox, city,
        		infoWindow = '';

                $("#lat_to").val('{!! $order->lat_to !!}');
                $("#long_to").val('{!! $order->long_to !!}');
                var latEl = document.querySelector( '#lat_to' );
        		var longEl = document.querySelector( '#long_to' );
                // init location

        		var addressEl = document.querySelector( '#maps-search' );

        		var element = document.getElementById( 'maps' );
        	    var city = document.querySelector( '.reg-input-city' );

        	mapOptions = {
        		// How far the maps zooms in.
        		zoom: 8,
        		// Current Lat and Long position of the pin/
                center: new google.maps.LatLng( 29.33744230597737, 48.022533337402336 ),

        		disableDefaultUI: false, // Disables the controls like zoom control on the map if set to true
        		scrollWheel: true, // If set to false disables the scrolling on the map.
        		draggable: true, // If set to false , you cannot move the map around.
        		// mapTypeId: google.maps.MapTypeId.HYBRID, // If set to HYBRID its between sat and ROADMAP, Can be set to SATELLITE as well.
        		// maxZoom: 11, // Wont allow you to zoom more than this
        		// minZoom: 9  // Wont allow you to go more up.

        	};

        	/**
        	 * Creates the map using google function google.maps.Map() by passing the id of canvas and
        	 * mapOptions object that we just created above as its parameters.
        	 *
        	 */
        	// Create an object map with the constructor function Map()
        	map = new google.maps.Map( element, mapOptions ); // Till this like of code it loads up the map.

        	/**
        	 * Creates the marker on the map
        	 *
        	 */
        	marker = new google.maps.Marker({
        		position: mapOptions.center,
        		map: map,
        		// icon: 'http://pngimages.net/sites/default/files/google-maps-png-image-70164.png',
        		draggable: true
        	});

        	/**
        	 * Creates a search box
        	 */
        	searchBox = new google.maps.places.SearchBox( addressEl );

        	/**
        	 * When the place is changed on search box, it takes the marker to the searched location.
        	 */
        	google.maps.event.addListener( searchBox, 'places_changed', function () {
        		var places = searchBox.getPlaces(),
        			bounds = new google.maps.LatLngBounds(),
        			i, place, lat, long, resultArray,
        			addresss = places[0].formatted_address;
        			$('#exampleModalLabeltof').text(addresss);
        			$('#exampleModalLabeltoHidden').val(addresss);
        			$('#exampleModalLabeltof').text(addresss);

        		for( i = 0; place = places[i]; i++ ) {
        			bounds.extend( place.geometry.location );
        			marker.setPosition( place.geometry.location );  // Set marker position new.
        		}

        		map.fitBounds( bounds );  // Fit to the bound
        		map.setZoom( 15 ); // This function sets the zoom to 15, meaning zooms to level 15.
        		// console.log( map.getZoom() );

        		lat = marker.getPosition().lat();
        		long = marker.getPosition().lng();
        		latEl.value = lat;
        		longEl.value = long;

        		resultArray =  places[0].address_components;

        		// Get the city and set the city input value to the one selected
        		for( var i = 0; i < resultArray.length; i++ ) {
        			if ( resultArray[ i ].types[0] && 'administrative_area_level_2' === resultArray[ i ].types[0] ) {
        				citi = resultArray[ i ].long_name;

        				// console.log(city)
        				// city.value = citi;
        			}
        		}

        		// Closes the previous info window if it already exists
        		if ( infoWindow ) {
        			infoWindow.close();
        		}
        		/**
        		 * Creates the info Window at the top of the marker
        		 */
        		infoWindow = new google.maps.InfoWindow({
        			content: addresss
        		});

        		infoWindow.open( map, marker );
        	} );


        	/**
        	 * Finds the new position of the marker when the marker is dragged.
        	 */
        	google.maps.event.addListener( marker, "dragend", function ( event ) {
        		var lat, long, address, resultArray, citi;

        		console.log( 'i am dragged' );
        		lat = marker.getPosition().lat();
        		long = marker.getPosition().lng();

        		var geocoder = new google.maps.Geocoder();
        		geocoder.geocode( { latLng: marker.getPosition() }, function ( result, status ) {
        			if ( 'OK' === status ) {  // This line can also be written like if ( status == google.maps.GeocoderStatus.OK ) {
        				address = result[0].formatted_address;
        				resultArray =  result[0].address_components;
        				$('#exampleModalLabeltof').text(address);
        			$('#exampleModalLabeltoHidden').val(address);
        			$('#exampleModalLabeltof').text(address);

        				// Get the city and set the city input value to the one selected
        				for( var i = 0; i < resultArray.length; i++ ) {
        					if ( resultArray[ i ].types[0] && 'administrative_area_level_2' === resultArray[ i ].types[0] ) {
        						citi = resultArray[ i ].long_name;
        						console.log( citi );
        					}
        				}
        				addressEl.value = address;
        				latEl.value = lat;
        				longEl.value = long;
                        console.log(lat)
        			} else {
        				console.log( 'Geocode was not successful for the following reason: ' + status );
        			}

        			// Closes the previous info window if it already exists
        			if ( infoWindow ) {
        				infoWindow.close();
        			}

        			/**
        			 * Creates the info Window at the top of the marker
        			 */
        			infoWindow = new google.maps.InfoWindow({
        				content: address
        			});

        			infoWindow.open( map, marker );
        		} );
        	});
            }

            return {
                // public functions
                init: function() {
                    // default charts
                    map1();
                    map2();
                }
            };
        }();
        jQuery(document).ready(function() {
            initMap.init();
        });
        @if(!auth()->user()->hasRole('superadministrator'))
        $(document).ready(function(){ //type_order,package
            $("select#package").change(function(){
                var selectedCountry = $(this).children("option:selected").val();
                if(selectedCountry === '1') {
                    $("#lat_from").prop('disabled', false);
                    $("#long_from").prop('disabled', false);
                } else {
                    $("#lat_from").prop('disabled', true);
                    $("#long_from").prop('disabled', true);
                }
            });
        });
        @endrole
        $(function() {
            $("#kt_form_1").validate({
                rules: {
                    phone: {
                        required: true,
                        number: true,
                        minlength: 8,
                        maxlength: 10,
                        // min:8,
                        // max:10,
                    },
                },
                messages: {
                    phone: {
                        required: '{{__('store.phoneRequires')}}',
                        number: '{{__('store.phoneNumber')}}',
                        minlength: '{{ __('auth.passwordMinEight') }}',
                        {{--min: '{{ __('auth.passwordMinEight') }}',--}}
                        maxlength: '{{ __('auth.passwordMaxEight') }}',
                        {{--max: '{{ __('auth.passwordMaxEight') }}',--}}
                    },
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
@endpush
@section('pageTitle')
    <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
    <span class="text-muted font-weight-bold mr-4">@lang('navbar.editOrder')</span>
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
                        <span class="fas fa-shopping-cart icon-lg"></span>
                    </h3>

                </div>

                <!--begin::Form-->
                <form method="post" action="{{Route('order.update', $order->id)}}">
                    @csrf
                    @method('put')
                    <input value="{{$order->lat_to}}" type="hidden" name="lat_to">
                    <input value="{{$order->long_to}}" type="hidden" name="long_to">
                    <input value="{{$order->long_from}}" id="exampleModalLabelfromHidden" type="hidden" name="long_from">
                    <input value="{{$order->lat_from}}" id="exampleModalLabelfromHidden" type="hidden" name="lat_from">
                    <input value="{{$order->from_address}}" id="exampleModalLabelfromHidden" type="hidden" name="from_address">
                    <input value="{{$order->to_address}}" id="exampleModalLabeltoHidden" type="hidden" name="to_address">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-lg-2 mb-5">
                                <label for="exampleSelect1">@lang('order.typeOfOrder')
                                    <span class="text-danger">*</span></label>
                                <select @change="onChangePackage($event)" name="type_order" class="form-control @error('type_order') is-invalid @enderror" id="package">
                                    <option value="">--</option>
                                    <option {{$order->type_order == 1 ? 'selected' : ''}} value="1">@lang('order.twoWay')</option>
                                    <option {{$order->type_order == 0 ? 'selected' : ''}} value="0">@lang('order.oneWay')</option>
                                </select>
                                @error('type_order')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-2">
                                <label>@lang('order.from_address')
                                    <span class="text-danger">*</span></label>
                                    <div class="input-group ">
                                        <div data-toggle="modal" data-target="#from_address_input" class="input-group-prepend">
                                                       <span style="color:#a4ac26" id="exampleModalLabelfromf">{{$order->from_address}}</span>
                                                        <span class="input-group-text">

                                                            <span class="svg-icon svg-icon-md"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo1\dist/../src/media/svg/icons\Map\Marker2.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                    <rect x="0" y="0" width="24" height="24"/>
                                                                    <path d="M9.82829464,16.6565893 C7.02541569,15.7427556 5,13.1079084 5,10 C5,6.13400675 8.13400675,3 12,3 C15.8659932,3 19,6.13400675 19,10 C19,13.1079084 16.9745843,15.7427556 14.1717054,16.6565893 L12,21 L9.82829464,16.6565893 Z M12,12 C13.1045695,12 14,11.1045695 14,10 C14,8.8954305 13.1045695,8 12,8 C10.8954305,8 10,8.8954305 10,10 C10,11.1045695 10.8954305,12 12,12 Z" fill="#000000"/>
                                                                </g>
                                                            </svg><!--end::Svg Icon--></span>
                                                        </span>
                                        </div>
                                        <input style="display: none"  id="lat_from" autocapitalize="off" autocomplete="off" value="{{ $order->lat_from }}" type="text" name="lat_from" class="form-control @error('lat_from') is-invalid @enderror" placeholder="@lang('order.from_addressDescription')" />
                                        <input style="display: none"  id="long_from" autocapitalize="off" autocomplete="off" value="{{ $order->long_from }}" type="text" name="long_from" class="form-control @error('long_from') is-invalid @enderror" placeholder="@lang('order.from_addressDescription')" />
                                    </div>
                                @error('lat_from')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                @error('long_from')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-2">
                                <label>@lang('order.to_address')
                                    <span class="text-danger">*</span></label>
                                    <div class="input-group ">
                                        <div data-toggle="modal" data-target="#to_address_input" class="input-group-prepend">
                                            <span style="color:#a4ac26" id="exampleModalLabeltof">{{$order->to_address}}</span>
                                                        <span class="input-group-text">

                                                            <span class="svg-icon svg-icon-md"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo1\dist/../src/media/svg/icons\Map\Marker2.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                    <rect x="0" y="0" width="24" height="24"/>
                                                                    <path d="M9.82829464,16.6565893 C7.02541569,15.7427556 5,13.1079084 5,10 C5,6.13400675 8.13400675,3 12,3 C15.8659932,3 19,6.13400675 19,10 C19,13.1079084 16.9745843,15.7427556 14.1717054,16.6565893 L12,21 L9.82829464,16.6565893 Z M12,12 C13.1045695,12 14,11.1045695 14,10 C14,8.8954305 13.1045695,8 12,8 C10.8954305,8 10,8.8954305 10,10 C10,11.1045695 10.8954305,12 12,12 Z" fill="#000000"/>
                                                                </g>
                                                            </svg><!--end::Svg Icon--></span>
                                                        </span>
                                        </div>
                                        <input style="display: none" id="lat_to" autocapitalize="off" autocomplete="off" value="{{$order->lat_to}}" type="text" name="lat_to" class="form-control @error('lat_to') is-invalid @enderror" placeholder="@lang('order.to_addressDescription')" />
                                        <input style="display: none" id="long_to" autocapitalize="off" autocomplete="off" value="{{$order->long_to}}" type="text" name="long_to" class="form-control @error('long_to') is-invalid @enderror" placeholder="@lang('order.to_addressDescription')" />
                                    </div>
                                @error('lat_to')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                @error('long_to')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-3">
                                <label>@lang('order.name')
                                    <span class="text-danger">*</span></label>
                                <input id="pac-input" autocapitalize="off" autocomplete="off" value="{{$order->name}}" type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="@lang('order.nameOfCustomer')" />

                                @error('name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-3">
                                <label>@lang('order.phone')
                                    <span class="text-danger">*</span></label>
                                    <input autocomplete="off" value="{{$order->phone}}" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" placeholder="@lang('order.phonePlaceholder')" />
                                @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-2">
                                <label>@lang('order.home')
                                    <span class="text-danger">*</span></label>
                                <input id="pac-input" autocapitalize="off" autocomplete="off" value="{{$order->home}}" type="text" name="home" class="form-control @error('home') is-invalid @enderror" />

                                @error('home')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-2">
                                <label>@lang('order.sabil')
                                    <span class="text-danger">*</span></label>
                                <input id="pac-input" autocapitalize="off" autocomplete="off" value="{{$order->sabil}}" type="text" name="sabil" class="form-control @error('sabil') is-invalid @enderror"/>

                                @error('sabil')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-2">
                                <label>@lang('order.street')
                                    <span class="text-danger">*</span></label>
                                <input id="pac-input" autocapitalize="off" autocomplete="off" value="{{$order->street}}" type="text" name="street" class="form-control @error('street') is-invalid @enderror"/>

                                @error('street')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-2">
                                <label>@lang('order.block')
                                    <span class="text-danger">*</span></label>
                                <input id="pac-input" autocapitalize="off" autocomplete="off" value="{{$order->block}}" type="text" name="block" class="form-control @error('block') is-invalid @enderror"/>

                                @error('block')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="col-lg-4">
                                <label for="exampleSelect1">@lang('order.description')</label>
                                <textarea autocomplete="off" name="description" class="form-control @error('description') is-invalid @enderror" />{{$order->description}}</textarea>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            
                        </div>
                        <div class="form-group row">
{{--                            @if(\Illuminate\Support\Facades\Auth::user()->store()->exists())--}}
                                @if (\Illuminate\Support\Facades\Auth::user()->package_hours()->exists() && $order->package_type == 1)
                                    <div class="col-lg-3">
                                        <label for="exampleSelect1">@lang('order.driver')</label>
                                        <select required v-model="form.package" @change="onChangePackage($event)" name="delivery_id" class="form-control @error('delivery_id') is-invalid @enderror" id="package">
                                            <option value="">--</option>
                                            @foreach($driverWithTime as $d)
                                                <option {{$order->delivery_id == $d->drivers->id ? 'selected' : ''}} value="{{$d->drivers->id}}">{{$d->drivers->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('delivery_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                @endif
{{--                            @endif--}}
                                <div class="col-lg-4 mt-5">
                                <label for="exampleSelect1">@lang('order.date_from')
                                    <span class="text-danger">*</span></label>
                                <div class="input-daterange input-group @error('start') is-invalid @enderror">
                                    <input autocomplete="off" name="date_from" type="date" value="{{\Carbon\Carbon::parse($order->date_from)->format('Y-m-d')}}" class="form-control"/>
                                    <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="la la-ellipsis-h"></i>
                                                                    </span>
                                    </div>
                                    <input value="{{$order->time_from}}" name="time_from" autocomplete="off" type="time" class="form-control"/>
                                </div>
                                @error('time_from')
                                    <span class="text-danger">{{ $message }}</span><br />
                                @enderror
                                @error('date_from')
                                    <span class="text-danger">{{ $message }}</span><br />
                                @enderror

                            </div>
                            <div class="col-lg-4 mt-5">
                                <label for="exampleSelect1">@lang('order.date_to')
                                    <span class="text-danger">*</span></label>
                                <div class="input-daterange input-group @error('start') is-invalid @enderror">
                                    <input name="date_to" value="{{\Carbon\Carbon::parse($order->date_to)->format('Y-m-d')}}" autocomplete="off" type="date" class="form-control"/>
                                    <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="la la-ellipsis-h"></i>
                                                                    </span>
                                    </div>
                                    <input name="time_to" value="{{$order->time_to}}" autocomplete="off" type="time" class="form-control"/>
                                </div>
                                @error('time_to')
                                    <span class="text-danger">{{ $message }}</span><br />
                                @enderror
                                @error('date_to')
                                    <span class="text-danger">{{ $message }}</span><br />
                                @enderror
                            </div>
                            
                            <div class="col-lg-4 mt-5">
                                <label for="exampleSelect1">@lang('order.totale_amount')</label>
                                <div class="input-daterange input-group @error('totale_amount') is-invalid @enderror" >
                                    <input autocomplete="off" value="{{$order->totale_amount}}" name="totale_amount" type="text" class="form-control @error('totale_amount') is-invalid @enderror" />
                                </div>
                                @error('totale_amount')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="col-lg-4 mt-5">
                                <label for="exampleSelect1">@lang('order.items')</label>
                                <div class="input-daterange input-group @error('items') is-invalid @enderror" >
                                    <input autocomplete="off" value="{{$order->items}}" name="items" type="text" class="form-control @error('items') is-invalid @enderror" />
                                </div>
                                @error('items')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="col-lg-4 mt-5">
                                <label for="exampleSelect1">@lang('order.Order Reference')</label>
                                <div class="input-daterange input-group @error('order_reference') is-invalid @enderror" >
                                    <input autocomplete="off" value="{{$order->order_reference}}" name="order_reference" type="text" class="form-control @error('order_reference') is-invalid @enderror" />
                                </div>
                                @error('order_reference')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary mr-2">@lang('form.update')</button>
                        <a href="{{Route('order.index')}}" class="btn btn-secondary">@lang('form.cancel')</a>
                    </div>
                </form>
                <!--end::Form-->
            </div>
            <!--end::Card-->
        </div>
    </div>


        <!-- Modal-->
<div class="modal fade" id="from_address_input" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">

                <div class="col-lg-12">
                    <label for="">
                        @lang('map.address'): <input id="map-search" class="form-control controls" type="text" placeholder="Search Box" size="104"></label><br>
                </div>
                    <div id="map" style="height: 400px; width: 100%;"></div>

                <div id="map-canvas"></div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">@lang('form.done')</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="to_address_input" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-lg-12">
                    <label for="">
                        @lang('map.address'): <input id="maps-search" class="form-control controls" type="text" placeholder="Search Box" size="104"></label><br>
                </div>
                    <div id="maps" style="height: 400px; width: 100%;"></div>

                <div id="map-canvas"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">@lang('form.done')</button>
            </div>
        </div>
    </div>
</div>
@endsection
