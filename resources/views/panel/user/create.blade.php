@extends('panel._layout')
@section('subTitle')
    @lang('navbar.newUser')
@endsection
@push('js')
<script
            src="{{"https://maps.googleapis.com/maps/api/js?key=AIzaSyDQDC3VV5CGRaueUYpEEJ308KNx8zbG5t0&language=".app()->getLocale()."&libraries=places&callback=initialize"}}"
            type="text/javascript">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
<script>
var initMap = function () {
            var map1 = function() {
                var mapOptions, map, marker, searchBox, city,
        		infoWindow = '',
        		addressEl = document.querySelector( '#map-search' ),
        		latEl = document.querySelector( '#lat' ),
        		longEl = document.querySelector( '#long' ),
        		element = document.getElementById( 'map' );
        	    city = document.querySelector( '.reg-input-city' );
        
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
        $('#address').val(addresss)
        $('#citys').text(addresss)
        
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
        
        				// Get the city and set the city input value to the one selected
        				for( var i = 0; i < resultArray.length; i++ ) {
        					if ( resultArray[ i ].types[0] && 'administrative_area_level_2' === resultArray[ i ].types[0] ) {
        						citi = resultArray[ i ].long_name;
        						console.log( citi );
        					}
        				}
        				addressEl.value = address;
        				$('#address').val(address)
                        $('#citys').text(address)
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
                }
            };
        }();
        jQuery(document).ready(function() {
            initMap.init();
        });



    $(function() {
        $("#kt_form_1").validate({
            rules: {
                 email: {
                    required: true,
                    email: true
                },
            },
            messages: {
                email: {
                    required: '{!!__('auth.emailRequires')!!}',
                    email: '{!!__('auth.emailChecked')!!}'
                },
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
    
    
</script>
@endpush
@push('style')
<style>
.pac-container {
    
    z-index : 200000;
}
    label#email-error {
        color: #f00;
    }
</style>
@endpush
@section('pageTitle')
    <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
    <span class="text-muted font-weight-bold mr-4">@lang('navbar.newUser')</span>
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
                        <span class="fas fa-user-tag icon-lg"></span>
                    </h3>

                </div>
                <!--begin::Form-->
                <form id="kt_form_1" method="post" action="{{Route('users.store')}}">
                    @csrf
                    <input type="hidden" id="lat" name="lat">
                    <input type="hidden" id="long" name="long">
                    <input type="hidden" id="address" name="address">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label>@lang('users.name')
                                    <span class="text-danger">*</span></label>
                                <input id="pac-input" autocapitalize="off" autocomplete="off" value="{{old('name')}}" type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="@lang('users.name')" />
                                @error('name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-3">
                                <label>@lang('users.email')
                                    <span class="text-danger">*</span></label>
                                    <input autocomplete="off" value="{{old('email')}}" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="@lang('users.emailPlaceholder')" />
                                @error('email')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-3">
                                <label>@lang('users.password')
                                    <span class="text-danger">*</span></label>
                                <div class="input-group ">
                                    <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <span class="svg-icon svg-icon-md"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo1\dist/../src/media/svg/icons\General\Lock.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                    <mask fill="white">
                                                                        <use xlink:href="#path-1"/>
                                                                    </mask>
                                                                    <g/>
                                                                    <path d="M7,10 L7,8 C7,5.23857625 9.23857625,3 12,3 C14.7614237,3 17,5.23857625 17,8 L17,10 L18,10 C19.1045695,10 20,10.8954305 20,12 L20,18 C20,19.1045695 19.1045695,20 18,20 L6,20 C4.8954305,20 4,19.1045695 4,18 L4,12 C4,10.8954305 4.8954305,10 6,10 L7,10 Z M12,5 C10.3431458,5 9,6.34314575 9,8 L9,10 L15,10 L15,8 C15,6.34314575 13.6568542,5 12,5 Z" fill="#000000"/>
                                                                </g>
                                                            </svg><!--end::Svg Icon-->
                                                        </span>
                                                    </span>
                                    </div>
                                    <input autocomplete="off" value="{{old('password')}}" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="@lang('users.passwordPlaceholder')" />

                                </div>
                                @error('password')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-2">
                                <label>@lang('store.address')
                                    <span class="text-danger">*</span></label>
                                <div class="input-group ">
                                    <div data-toggle="modal" data-target="#from_address_input" class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <span class="svg-icon svg-icon-md"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Map/Marker1.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                    <rect x="0" y="0" width="24" height="24"/>
                                                                    <path d="M5,10.5 C5,6 8,3 12.5,3 C17,3 20,6.75 20,10.5 C20,12.8325623 17.8236613,16.03566 13.470984,20.1092932 C12.9154018,20.6292577 12.0585054,20.6508331 11.4774555,20.1594925 C7.15915182,16.5078313 5,13.2880005 5,10.5 Z M12.5,12 C13.8807119,12 15,10.8807119 15,9.5 C15,8.11928813 13.8807119,7 12.5,7 C11.1192881,7 10,8.11928813 10,9.5 C10,10.8807119 11.1192881,12 12.5,12 Z" fill="#000000" fill-rule="nonzero"/>

                                                                </g>
                                                            </svg><!--end::Svg Icon-->

                                                        </span>
                                                    </span>
                                    </div>
                                    <span id="citys"></span>
                                </div>
                                @error('lat')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                                @error('long')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                                @error('address')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary mr-2">@lang('form.create')</button>
                        <a href="{{Route('users.store')}}" class="btn btn-secondary">@lang('form.cancel')</a>
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
                <button type="button" id="submitFrom" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">@lang('form.done')</button>
            </div>
        </div>
    </div>
</div>
@endsection
