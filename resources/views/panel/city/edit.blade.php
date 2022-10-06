@extends('panel._layout')
@section('subTitle')
    @lang('navbar.createDelivery')
@endsection
@push('js')
    <script async defer
            src="{{"https://maps.googleapis.com/maps/api/js?key=AIzaSyDQDC3VV5CGRaueUYpEEJ308KNx8zbG5t0&language=".app()->getLocale()."&libraries=places&callback=initMap"}}"
            type="text/javascript">
    </script>
    <!-- Async script executes immediately and must be after any DOM elements used in callback. -->
    <script type="text/javascript">
        function initMap() {
            $("#latLocation").val('{!! $city->lat !!}');
            $("#longLocation").val('{!! $city->long !!}');
            // init location
            var gaza = {lat: {!! $city->lat !!}, lng: {!! $city->long !!}};
            // The map, centered init location
            var map = new google.maps.Map(
                document.getElementById('map'), {zoom: 12, center: gaza});
            // The marker, positioned init location
            var marker = new google.maps.Marker({position: gaza, map: map, draggable: true});

            google.maps.event.addListener(marker, 'dragend', function(markerEvent) {//dragstart - drag - dragend
                $("#latLocation").val(markerEvent.latLng.lat());
                $("#longLocation").val(markerEvent.latLng.lng());
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
        				// addressEl.value = address;
        				// latEl.value = lat;
        				// longEl.value = long;
        				$('#city_name').val(address)
        				$('#city_name_ar').val(address)
        				$('#city_name_en').val(address)
        			} else {
        				console.log( 'Geocode was not successful for the following reason: ' + status );
        			}

        			/**
        			 * Creates the info Window at the top of the marker
        			 */
        			infoWindow = new google.maps.InfoWindow({
        				content: address
        			});

        			infoWindow.open( map, marker );
        		} );
            })
        }
    </script>
@endpush
@section('pageTitle')
    <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
    <span class="text-muted font-weight-bold mr-4">@lang('navbar.createDelivery')</span>
    {{--<a href="#" class="btn btn-light-warning font-weight-bolder btn-sm">Add New</a>--}}
@endsection
@section('content')
    <!--begin::Row-->
    <div class="row">
        <div class="col-md-12">
            <form method="post" action="{{Route('city.update', $city->id)}}">
                @csrf
                @method('put')
                <input value="{{$city->name}}" type="hidden" name="city" id="city_name">
                <div class="col-lg-12 col-sm-12">
                    <div class="form-group row">
                        <div class="col-lg-3">
                            
                            <label>@lang('area.nameOfAreaAr') <span class="text-danger">*</span></label>
                            <input value="{{ object_get(json_decode($city->getAttributes()['name']), 'ar') }}" type="text" name="name[ar]" id="city_name_ar"
                                   class="form-control @error('name.ar') is-invalid @enderror"/>
                            @error('name.ar')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-lg-3">
                            <label>@lang('area.nameOfAreaEn') <span class="text-danger">*</span></label>
                            <input value="{{ object_get(json_decode($city->getAttributes()['name']), 'en') }}" type="text" name="name[en]" id="city_name_en"
                                   class="form-control @error('name.en') is-invalid @enderror"/>
                            @error('name.en')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-lg-3">
                            <label>@lang('map.lat') <span class="text-danger">*</span></label>
                            <input type="text" name="lat" id="latLocation"
                                   class="form-control @error('lat') is-invalid @enderror" value="{{$city->lat}}"/>
                            @error('lat')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-lg-3">
                            <label>@lang('map.long') <span class="text-danger">*</span></label>
                            <input type="text" name="long" id="longLocation"
                                   class="form-control @error('long') is-invalid @enderror" value="{{$city->long}}"/>
                            @error('long')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-lg-2">
                            <label>@lang('map.price') <span class="text-danger">*</span></label>
                            <input type="text" name="price" id="longLocation"
                                   class="form-control @error('price') is-invalid @enderror" value="{{$city->price}}"/>
                            @error('price')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-lg-2">
                            <label>@lang('form.status')
                                <span class="text-danger">*</span></label>
                            <select name="status" class="form-control @error('status') is-invalid @enderror" id="exampleSelect1">
                                <option value=""></option>
                                <option {{$city->status == '1' ? 'selected' : '' }} value="1">@lang('form.active')</option>
                                <option {{$city->status == '0' ? 'selected' : '' }} value="0">@lang('form.inactive')</option>
                            </select>
                            @error('status')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-lg-2">
                            <label>@lang('city.area')
                                <span class="text-danger">*</span></label>
                            <select name="area_id" class="form-control @error('area_id') is-invalid @enderror" id="exampleSelect1">
                                <option value=""></option>
                                @foreach($areas as $key => $area)
                                    <option {{$city->area_id == $area->id ? 'selected' : '' }} value="{{$area->id}}">{{$area->name}}</option>
                                @endforeach
                            </select>
                            @error('area_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <button class="btn btn-primary mt-5" type="submit">@lang('form.update')</button>
                    </div>
                    
                </div>
            </form>
            <!--begin::Card-->
            <div class="form-group">
                <div id="map" style="height: 400px; width: 100%;">

                </div>

            </div>
            <!--end::Card-->
        </div>
    </div>
@endsection
