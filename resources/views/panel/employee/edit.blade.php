@extends('panel._layout')
@section('subTitle')
    @lang('navbar.editUser')
@endsection
@push('js')
<script src="{{"https://maps.googleapis.com/maps/api/js?key=AIzaSyDQDC3VV5CGRaueUYpEEJ308KNx8zbG5t0&language=".app()->getLocale()."&libraries=places&callback=initialize"}}"
            type="text/javascript"></script>
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
        
        </script>
@endpush
@push('style')
<style>
.pac-container {
    
    z-index : 200000;
}
    
</style>
@endpush
@section('pageTitle')
    <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
    <span class="text-muted font-weight-bold mr-4">@lang('navbar.editUser')</span>
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
                <form method="post" action="{{Route('users.update.vendor', $user->id)}}">
                    @csrf
                    @method('put')
                    <input value="{{$user->lat}}" type="hidden" id="lat" name="lat">
                    <input value="{{$user->id}}" type="hidden" name="user_id">
                    <input value="{{$user->long}}" type="hidden" id="long" name="long">
                    <input value="{{$user->address}}" type="hidden" id="address" name="address">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label>@lang('users.name')
                                    <span class="text-danger">*</span></label>
                                <input id="pac-input" autocapitalize="off" autocomplete="off" value="{{$user->name}}" type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="@lang('users.name')" />
                                @error('name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                                @if(!$errors->has('from_address'))
                                    <span class="form-text text-muted">@lang('users.nameDescription').</span>
                                @endif
                            </div>
                            <div class="col-lg-3">
                                <label>@lang('users.email')
                                    <span class="text-danger">*</span></label>
                                <div class="input-group ">
                                    <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <span class="svg-icon svg-icon-md"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo1\dist/../src/media/svg/icons\Communication\Mail-at.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                    <rect x="0" y="0" width="24" height="24"/>
                                                                    <path d="M11.575,21.2 C6.175,21.2 2.85,17.4 2.85,12.575 C2.85,6.875 7.375,3.05 12.525,3.05 C17.45,3.05 21.125,6.075 21.125,10.85 C21.125,15.2 18.825,16.925 16.525,16.925 C15.4,16.925 14.475,16.4 14.075,15.65 C13.3,16.4 12.125,16.875 11,16.875 C8.25,16.875 6.85,14.925 6.85,12.575 C6.85,9.55 9.05,7.1 12.275,7.1 C13.2,7.1 13.95,7.35 14.525,7.775 L14.625,7.35 L17,7.35 L15.825,12.85 C15.6,13.95 15.85,14.825 16.925,14.825 C18.25,14.825 19.025,13.725 19.025,10.8 C19.025,6.9 15.95,5.075 12.5,5.075 C8.625,5.075 5.05,7.75 5.05,12.575 C5.05,16.525 7.575,19.1 11.575,19.1 C13.075,19.1 14.625,18.775 15.975,18.075 L16.8,20.1 C15.25,20.8 13.2,21.2 11.575,21.2 Z M11.4,14.525 C12.05,14.525 12.7,14.35 13.225,13.825 L14.025,10.125 C13.575,9.65 12.925,9.425 12.3,9.425 C10.65,9.425 9.45,10.7 9.45,12.375 C9.45,13.675 10.075,14.525 11.4,14.525 Z" fill="#000000"/>
                                                                </g>
                                                            </svg><!--end::Svg Icon-->
                                                        </span>
                                                    </span>
                                    </div>
                                    <input autocomplete="off" value="{{$user->email}}" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="@lang('users.emailPlaceholder')" />
                                </div>
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
                                    <input autocomplete="off" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="@lang('users.passwordPlaceholder')" />
                                </div>
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
                                    <span id="citys">{{$user->address}}</span>
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
                        <button type="submit" class="btn btn-primary mr-2">@lang('form.update')</button>
                        <a href="{{Route('users.index.vendor')}}" class="btn btn-secondary">@lang('form.cancel')</a>
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
