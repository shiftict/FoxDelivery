@extends('panel._layout')
@section('subTitle')
    @lang('navbar.createDelivery')
@endsection
@push('js')

    {{--    <script src="{{asset('panel/assets/js/pages/crud/forms/widgets/bootstrap-timepicker.js')}}"></script>--}}

    <script src="{{asset('panel/js/dropzone.js')}}"></script>
    <script
            src="{{"https://maps.googleapis.com/maps/api/js?key=AIzaSyDQDC3VV5CGRaueUYpEEJ308KNx8zbG5t0&language=".app()->getLocale()."&libraries=places"}}"
            type="text/javascript">
    <script src="{{asset('panel/assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('panel/assets/js/pages/crud/forms/widgets/form-repeater.js')}}"></script>

    <script src="https://unpkg.com/vue@next"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <script>
        const AttributeBinding = {

            data() {
                return {
                    form: {
                        'nameAr': '',
                        'nameEn': '',
                        'phone': '',
                        'email': '',
                        'lat': '',
                        'cityy': '',
                        'long': '',
                        'package': '',
                        'status': '',
                        'street': '',
                        'sabil': '',
                        'home': '',
                        'block': '',
                        'citys': '',
                        // 'employee_id': '',
                    }, // form data
                    selectedIndex: '',
                    getDelivery: '{!! Route('get_drivers_vue') !!}', // route get delivery
                    getStoreyDataRoute: '{!! Route('vendor.show', $vendor->id) !!}', // route get delivery
                    putDelivery: '{!! Route('vendor.update', $vendor->id) !!}', // route post data
                    getEmployee: '{!! Route('employee.active') !!}',
                    getArea: '{!! Route('get_rea') !!}',
                    listDelivery: [], // list delivery
                    idStore: '{!! $vendor->id !!}', // list delivery
                    // listEmployee: [], // list employee
                    //selectedDriver: '',
                    // selectedIndex: '',
                    //validation
                    validNameAr: '',
                    validNameEn: '',
                    validPhone: '',
                    validStatus: '',
                    validLat: '',
                    validLong: '',
                    optionsCity: [],
                    optionsArea: [],
                    selected_area: '',
                    selected_city: '',
                }
            },
            created() {
                
                this.getDataStore()
                this.getAreaf()
                // this.getEmployeeFunction()
            },
            mounted() {
            },
            watch: {
                // selectedIndex: function (newValue, oldValue) {
                //     $(this.$el).val(newValue).trigger('change');
                // },
                nameAr:{
                    handler: function(val) {
                        this.form.lat = $("input[name=lat_id]").val()
                        this.form.long = $("input[name=long_id]").val()
                    }
                },
                nameEn:{
                    handler: function(val) {
                        this.form.lat = $("input[name=lat_id]").val()
                        this.form.long = $("input[name=long_id]").val()
                    }
                },
                phone:{
                    handler: function(val) {
                        this.form.lat = $("input[name=lat_id]").val()
                        this.form.long = $("input[name=long_id]").val()
                    }
                },
                status:{
                    handler: function(val) {
                        this.form.lat = $("input[name=lat_id]").val()
                        this.form.long = $("input[name=long_id]").val()
                    }
                },
            },
            computed: {
                nameAr() {
                return this.form.nameAr;
                },
                nameEn() {
                return this.form.nameEn;
                },
                phone() {
                return this.form.nameEn;
                },
                lat() {
                return this.form.nameAr;
                },
                long() {
                return this.form.nameEn;
                },
                status() {
                return this.form.nameEn;
                },
            },
            methods: {
            //     getEmployeeFunction() {
            //     // csrf token
            //     let myToken =  '{!! csrf_token() !!}'
            //     axios.defaults.headers.common = {
            //         'X-Requested-With': 'XMLHttpRequest',
            //         'X-CSRF-TOKEN': myToken
            //     };
            //     // request post with data start time - end time
            //     axios.get(this.getEmployee).then(response => {
            //         // console.log(response.data.employee)
            //         // this.listDelivery = response.data.delivery
            //         this.listEmployee = response.data.employee
            //     })
            // },
                onChangeEmployee(event) {
                    this.form.employee_id = event.target.value
                    console.log(event.target.value)
                },
                getDataStore() {
                    axios.get(this.getStoreyDataRoute).then(response => {
                        // this.listDelivery = response.data.delivery
                        let mainData = response.data.vendor
                        this.form.nameAr = response.data.vendor.nameAr
                        this.form.nameEn = response.data.vendor.nameAr
                        this.form.email = response.data.vendor.email
                        this.form.phone = response.data.vendor.phone
                        this.form.cityy = response.data.vendor.city
                        this.form.lat = response.data.vendor.lat
                        this.form.long = response.data.vendor.long
                        this.form.status = response.data.vendor.status
                        this.selectedIndex = response.data.vendor.employee
                        this.form.employee_id = response.data.vendor.employee
                        this.form.home = response.data.vendor.home
                        this.form.block = response.data.vendor.block
                        this.form.sabil = response.data.vendor.sabil
                        this.form.street = response.data.vendor.street
                        this.form.citys = response.data.vendor.citys
                        this.selected_city = response.data.vendor.citys
                        $('#cityy').val(this.form.cityy)
                        $("input[name=lat_id]").val(response.data.vendor.lat)
                        $("input[name=long_id]").val(response.data.vendor.long)
                    })
                },
                onChangeStatus(event, index) {
                    let statusId = event.target.value
                    this.form.status = statusId
                },
                sendData() {
                    let myToken =  '{!! csrf_token() !!}'
                    axios.defaults.headers.common = {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': myToken
                    };
                    this.form.lat = $("input[name=lat_id]").val()
                    this.form.long = $("input[name=long_id]").val()
                        this.form.cityy = $('#cityy').val()
                    axios.put(this.putDelivery,  this.form).then(response => {
                        // this.listDelivery = response.data.delivery
                        // console.log('bbbbbbbbbb')
                            toastr.options = {
                                "closeButton": false,
                                "debug": false,
                                "newestOnTop": false,
                                "progressBar": true,
                                "positionClass": "toast-top-right",
                                "preventDuplicates": false,
                                "onclick": null,
                                "showDuration": "300",
                                "hideDuration": "1000",
                                "timeOut": "5000",
                                "extendedTimeOut": "1000",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                            }
                            toastr.success('@lang("alert.successUpdate")')
                            // console.log('aaa')
                            this.clear()
                        window.location.href = '{!! Route('vendor.index') !!}';
                    }).catch((error) => {
                        // console.log('cccccc')
                        if(error.response.data.errors.lat != undefined){
                            this.validLat = error.response.data.errors.lat[0];
                        }
                        if(error.response.data.errors.long != undefined){
                            this.validLong = error.response.data.errors.long[0];
                        }
                        if(error.response.data.errors.nameAr != undefined){
                            this.validNameAr = error.response.data.errors.nameAr[0];
                        }
                        if(error.response.data.errors.nameEn != undefined){
                            this.validNameEn = error.response.data.errors.nameEn[0];
                        }
                        if(error.response.data.errors.phone != undefined){
                            this.validPhone = error.response.data.errors.phone[0];
                        }
                        if(error.response.data.errors.status != undefined){
                            this.validStatus = error.response.data.errors.status[0];
                        }
                        {{--'{!! toastr()->info('Are you the 6 fingered man?') !!}'--}}
                        // console.log(error.response.data.errors); //Logs a string: Error: Request failed with status code 404
                    });
                },

                clear() {
                    this.validNameAr= ''
                    this.validNameEn= ''
                    this.validPhone= ''
                    this.validStatus= ''
                    this.validLat= ''
                    this.validLong= ''
                },
                getAreaf(){
                    let self = this
                    axios.get(this.getArea).then(response => {
                        this.optionsArea = response.data.data
                        this.optionsArea.filter((val, index) => {
                            val.cities.filter((valc, indexc) => {
                                    if(valc.id == self.form.citys) {
                                        self.selected_area = val.id
                                        self.optionCitsArea(val.id)
                                        // console.log(self.form.citys)
                                    }
                                });
                        });
                    })
                },
                optionCitsArea(id) {
                    // console.log(id)
                    this.optionsArea.filter((val, index) => {
                        if(val.id == id) {
                            // console.log(this.form.citys)
                            this.optionsCity = ''
                            this.optionsCity = val.cities
                        }
                    });
                },
                onChangeArea(event) {
                    let self = this
                    this.optionsCity = event.target.value
                    this.optionsCity = ''
                    this.optionsArea.filter((val, index) => {
                        if(val.id == event.target.value) {
                            self.optionsCity = ''
                            self.optionsCity = val.cities
                        }
                    });
                },
                onChangeCity(event) {
                    this.form.citys = event.target.value
                },
            },
        }

        Vue.createApp(AttributeBinding).mount('#app')
    </script>
    <script>
       var initMap = function () {
            var map1 = function() {
                var mapOptions, map, marker, searchBox, city,
        		infoWindow = '',
        		addressEl = document.querySelector( '#map-search' ),
        		latEl = document.querySelector( '#lat_id' ),
        		longEl = document.querySelector( '#long_id' ),
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
        $('#cityy').val(addresss)
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
        				city.value = citi;
        				console.log(city)
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
        				$('#cityy').val(address)
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
html, body, #map-canvas {
	height: 100%;
	margin: 0;
	padding: 0;
}

#map-canvas {
	height: 75%;
	width: 75%;
}

label {
	padding: 20px 10px;
	display: inline-block;
	font-size: 1.5em;
}

input {
	font-size: 0.75em;
	padding: 10px;
}

label {
	padding: 20px 10px;
	display: inline-block;
	font-size: 1.5em;
}

input {
	font-size: 0.75em;
	padding: 10px;
}
</style>
@endpush
@section('pageTitle')
    <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
    <span class="text-muted font-weight-bold mr-4">@lang('navbar.editStore')</span>
    {{--<a href="#" class="btn btn-light-warning font-weight-bolder btn-sm">Add New</a>--}}
@endsection
@section('content')
    <!--begin::Row-->
    <div class="row" id="app">
        @if (count($errors) > 0)
            <div class = "alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="col-md-12">
            <!--begin::Card-->
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header">
                    <h3 class="card-title">
                        <span class="fas la-store icon-lg"></span>
                    </h3>

                </div>
                <!--begin::Form-->
                <form method="post" action="{{Route('vendor.store')}}">
                    @csrf
                    <input type="hidden" name="cityy" id="cityy">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-lg-3 mb-5">
                                <label>@lang('store.nameAr')
                                    <span class="text-danger">*</span></label>
                                <input v-model="form.nameAr" autocomplete="off" type="text" name="name[ar]" class="form-control @error('name.ar') is-invalid @enderror" placeholder="@lang('store.namePlaceholderAr')" />
                                <span v-if="validNameAr" class="text-danger">@{{ validNameAr }}</span>
                            </div>
                            <div class="col-lg-3">
                                <label>@lang('store.nameEn')
                                    <span class="text-danger">*</span></label>
                                <input v-model="form.nameEn" autocomplete="off" type="text" name="name[en]" class="form-control @error('name.en') is-invalid @enderror" placeholder="@lang('store.namePlaceholderEn')" />
                                <span v-if="validNameEn" class="text-danger">@{{ validNameEn }}</span>
                            </div>
                            <div class="col-lg-3">
                                <label>@lang('store.phone')
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
                                    <input v-model="form.phone" autocomplete="off" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" placeholder="@lang('store.phonePlaceholder')" />
                                </div>
                                <span v-if="validPhone" class="text-danger">@{{ validPhone }}</span>
                            </div>
                            <div class="col-lg-3">
                                <label>@lang('store.email')
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
                                    <input disabled autocomplete="off" v-model="form.email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="@lang('store.emailPlaceholder')" />
                                </div>
                            </div>
                            <!--<div class="col-lg-4">-->
                            <!--    <label for="exampleSelect1">@lang('store.employee')-->
                            <!--        <span class="text-danger">*</span></label>-->
                            <!--    <select v-model="form.employee_id" @change="onChangeEmployee($event)" name="employee_id" class="form-control @error('employee_id') is-invalid @enderror" id="exampleSelect1">-->
                            <!--        <option value="">--</option>-->
                            <!--        <option :selected="selectedIndex" v-for="em in listEmployee" :value="em.id">-->
                            <!--                            @{{ em.name }}-->
                            <!--                        </option>-->
                            <!--    </select>-->
                            <!--    <span v-if="validStatus" class="text-danger">@{{ validStatus }}</span>-->
                            <!--</div>-->
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-2">
                                <label for="exampleSelect1">@lang('navbar.area')</label>
                                <select @change="onChangeArea($event)" name="status" class="form-control @error('status') is-invalid @enderror" id="exampleSelect1">
                                    <option value="">--</option>
                                    <option v-for="option in optionsArea" :selected="option.id == selected_area" :value="option.id">@{{option.name['ar']}}</option>
                                </select>
                                <span v-if="validCity" class="text-danger">@{{ validCity }}</span>
                            </div>
                            <div class="col-lg-2">
                                <label for="exampleSelect1">@lang('navbar.city')</label>
                                <select @change="onChangeCity($event)" name="status" class="form-control @error('status') is-invalid @enderror" id="exampleSelect1">
                                    <option value="">--</option>
                                    <option v-for="option in optionsCity" :selected="option.id == selected_city"  :value="option.id">@{{option.name}}</option>
                                </select>
                                <span v-if="validCity" class="text-danger">@{{ validCity }}</span>
                            </div>
                            <div class="col-lg-2">
                                <label>@lang('order.sabil')</label>
                                <input v-model="form.sabil"autocomplete="off" value="{{old('sabil')}}" type="text" class="form-control @error('sabil') is-invalid @enderror" name="sabil" placeholder="@lang('order.sabil')" />

                            </div>
                            <div class="col-lg-2">
                                <label>@lang('order.street')</label>
                                <input v-model="form.street" autocomplete="off" value="{{old('street')}}" type="text" class="form-control @error('street') is-invalid @enderror" name="street" placeholder="@lang('order.street')" />

                            </div>
                            <div class="col-lg-2">
                                <label>@lang('order.block')</label>
                                <input v-model="form.block" autocomplete="off" value="{{old('block')}}" type="text" class="form-control @error('block') is-invalid @enderror" name="block" placeholder="@lang('order.block')" />

                            </div>
                            <div class="col-lg-2">
                                <label>@lang('order.home')</label>
                                <input v-model="form.home" autocomplete="off" value="{{old('block')}}" type="text" class="form-control @error('block') is-invalid @enderror" name="block" placeholder="@lang('order.block')" />

                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label for="exampleSelect1">@lang('form.status')
                                    <span class="text-danger">*</span></label>
                                <select v-model="form.status" @change="onChangeStatus($event)" name="status" class="form-control @error('status') is-invalid @enderror" id="exampleSelect1">
                                    <option value="">--</option>
                                    <option value="1">@lang('form.active')</option>
                                    <option value="0">@lang('form.inactive')</option>
                                </select>
                                <span v-if="validStatus" class="text-danger">@{{ validStatus }}</span>
                            </div>
                            <div class="col-lg-4">
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
                                    <input id="lat_id" v-model="form.lat" autocomplete="off" type="text" class="form-control @error('address') is-invalid @enderror" name="lat_id" placeholder="@lang('store.addressPlaceholder')" />
                                    <input id="long_id" v-model="form.long" autocomplete="off" type="text" class="form-control @error('address') is-invalid @enderror" name="long_id" placeholder="@lang('store.addressPlaceholder')" />
                                </div>
                                <span v-if="validLat" class="text-danger">@{{ validLat }}</span><br />
                                <span v-if="validLong" class="text-danger">@{{ validLong }}</span>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="button" @click="sendData" class="btn btn-primary mr-2">@lang('form.update')</button>
                        <a href="{{Route('vendor.index')}}" class="btn btn-secondary">@lang('form.cancel')</a>
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
@endsection
