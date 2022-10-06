<!DOCTYPE html>
<html lang="{{app()->getLocale()}}" @if(app()->getLocale() == 'ar')direction="rtl" style="direction: rtl;"@else direction="ltr" style="direction: ltr;" @endif>
<!--begin::Head-->
<head><base href="">
    <meta charset="utf-8" />
    <title>Fox Delivery | @yield('subTitle')</title>
    <meta name="description" content="Fox delivery service covers all parts of Kuwait to give you the best and fastest." />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="canonical" href="https://foxdelivery.store/" />
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    @toastr_css
    @include('panel.include.style')
    @stack('style')
    <style>
        .hh-grayBox {
	background-color: #F8F8F8;
	margin-bottom: 20px;
	padding: 35px;
  margin-top: 20px;
}
.pt45{padding-top:45px;}
.order-tracking{
	text-align: center;
	width: 33.33%;
	position: relative;
	display: block;
}
.order-tracking .is-complete{
	display: block;
	position: relative;
	border-radius: 50%;
	height: 30px;
	width: 30px;
	border: 0px solid #AFAFAF;
	background-color: #f7be16;
	margin: 0 auto;
	transition: background 0.25s linear;
	-webkit-transition: background 0.25s linear;
	z-index: 2;
}
.order-tracking .is-complete:after {
	display: block;
	position: absolute;
	content: '';
	height: 14px;
	width: 7px;
	top: -2px;
	bottom: 0;
	left: 5px;
	margin: auto 0;
	border: 0px solid #AFAFAF;
	border-width: 0px 2px 2px 0;
	transform: rotate(45deg);
	opacity: 0;
}
.order-tracking.completed .is-complete{
	border-color: #27aa80;
	border-width: 0px;
	background-color: #27aa80;
}
.order-tracking.completed .is-complete:after {
	border-color: #fff;
	border-width: 0px 3px 3px 0;
	width: 7px;
	left: 11px;
	opacity: 1;
}
.order-tracking p {
	color: #A4A4A4;
	font-size: 16px;
	margin-top: 8px;
	margin-bottom: 0;
	line-height: 20px;
}
.order-tracking p span{font-size: 14px;}
.order-tracking.completed p{color: #000;}
.order-tracking::before {
	content: '';
	display: block;
	height: 3px;
	width: calc(100% - 40px);
	background-color: #f7be16;
	top: 13px;
	position: absolute;
	left: calc(-50% + 20px);
	z-index: 0;
}
.order-tracking:first-child:before{display: none;}
.order-tracking.completed:before{background-color: #27aa80;}

    </style>
</head>
<!--end::Head-->
<!--begin::Body-->
<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
<!--begin::Main-->
<div class="container">
                    <div class="row">
						<div class="col-12 col-md-12 hh-grayBox pt45 pb20">
							<div class="row justify-content-between">
								<div class="order-tracking">
									<span class="is-complete"></span>
									<p>تم التوصيل<br>
									<!--<span>Mon, June 24</span>-->
									</p>
								</div>
								<div class="order-tracking completed">
									<span class="is-complete"></span>
									<p>جاري التوصيل<br>
									<!--<span>Tue, June 25</span>-->
									</p>
								</div>
								<div class="order-tracking completed">
									<span class="is-complete"></span>
									<p>تم التحميل<br>
									<span>#{{request()->segment(2)}}</span>
									</p>
								</div>
							</div>
						</div>
						
						<div class="col-12 col-md-12 hh-grayBox pt45 pb20">
						    <div class="col-lg-12 col-xxl-12">
                                <!--begin::Stats Widget 11-->
                                <div style="height: 400px; width: 100%;" id="map">
                                        </div>
                                <!--end::Stats Widget 11-->
                            </div>
						</div>
					</div>
</div>
<!--end::Scrolltop-->

<script>var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";</script>
<!--begin::Global Config(global config for global JS scripts)-->
<script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1400 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#3699FF", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#E4E6EF", "dark": "#181C32" }, "light": { "white": "#ffffff", "primary": "#E1F0FF", "secondary": "#EBEDF3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#3F4254", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#EBEDF3", "gray-300": "#E4E6EF", "gray-400": "#D1D3E0", "gray-500": "#B5B5C3", "gray-600": "#7E8299", "gray-700": "#5E6278", "gray-800": "#3F4254", "gray-900": "#181C32" } }, "font-family": "Poppins" };</script>
<!--end::Global Config-->
@include('panel.include.alert')


<!--begin::Global Theme Bundle(used by all pages)-->
<script src="{{asset('panel/assets/js/custom.js')}}"></script>
<script src="{{asset('panel/assets/plugins/global/plugins.bundle.js')}}"></script>
<script src="{{asset('panel/assets/plugins/custom/prismjs/prismjs.bundle.js')}}"></script>
<script src="{{asset('panel/assets/js/scripts.bundle.js')}}"></script>
{{--<script src="https://keenthemes.com/metronic/assets/js/engage_code.js"></script>--}}
<!--end::Global Theme Bundle-->
<!--begin::Page Vendors(used by this page)-->
<script src="{{asset('panel/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js')}}"></script>
<!--end::Page Vendors-->
<!--begin::Page Scripts(used by this page)-->
<script src="{{asset('panel/assets/js/pages/widgets.js')}}"></script>
<!--end::Page Scripts-->
<script
        src="{{"https://maps.googleapis.com/maps/api/js?key=AIzaSyDQDC3VV5CGRaueUYpEEJ308KNx8zbG5t0&language=".app()->getLocale()."&callback=initMap&v=weekly"}}"
        type="text/javascript">
</script>
<script>
    jQuery(document).ready(function() {
            let map;
            let multipleMarkers = [];
            
            var markers = {!! json_encode($order) !!};
            var driver = markers.id
            
            setInterval(function() {
           $.ajax({
                      url: '{!!route('map.track.order', request()->segment(2))!!}',
                      type: "get", //send it through get method
                      /*data: { 
                        ajaxid: 4, 
                        UserID: UserID, 
                        EmailAddress: EmailAddress
                      },*/
                      success: function(response) {
                        //Do Something
                        // newPostion()
                        // markers = []
                        // console.log(response.delivery)
                        markers = response
                        // $.each(response.delivery, function(k, v) {
                            updatePositionOfSpecificMarker(response.id, response.lat, response.lng)
                            
                        // makeMarrker(v.id, v.name, v.lat, v.lng)
                            // markers.push(v)
                        // });
                        console.log(response)
                        makeMarrker()
                        // 
                        // console.log(markers)
                      },
                      error: function(xhr) {
                        //Do Something to handle error
                      }
                    });
            }, 15000); // 
            
            const icon = {
                url: "https://static.thenounproject.com/png/331565-200.png",
                scaledSize: new google.maps.Size(50, 50),
            };
            function initMap() {
                map = new google.maps.Map(document.getElementById("map"), {
                                center: { lat:29.33744230597737, lng: 47.945629040527336 },
                                zoom: 8,
                              });
                // for(let i=0; i < markers.length;i++){
                //console.log(markers[0]);
                     var marker = new google.maps.Marker({
                        position:{lat:parseFloat(markers.lat),lng:parseFloat(markers.lng)},
                        map:map,
                        animation: google.maps.Animation.DROP,
                        title:markers.driver,
                        draggable:false,
                        icon: icon
                    });
                    
                    marker.set('id', markers.id);
                    multipleMarkers.push(marker);
                // }
            }
            
            function updatePositionOfSpecificMarker(id, newLat, newLng)
            {
                console.log(multipleMarkers)
                // DeleteMarkers()
                for (i = 0; i <multipleMarkers.length; i++) {
                    let marker = multipleMarkers[i];
                    // console.log(marker)
                    //if ids don't match - return early
                    if (!marker.get('id') == id) {
                        return;
                    }
                    multipleMarkers[i].setMap(null);
                    // const latLng = { lat: parseFloat(newLat), lng: parseFloat(newLng) };
                    // console.log(latLng)
                    // marker.setPosition(latLng);
                    // multipleMarkers[i] = marker;
                    
                    
                }
                // markers = [];
                // new google.maps.Marker({
                //         position:{lat:parseFloat(newLat),lng:parseFloat(newLng)},
                //         map:map,
                //         // animation: google.maps.Animation.DROP,
                //         title:'sss',
                //         draggable:false,
                //         icon: icon
                //     });
                
                // var latlng = new google.maps.LatLng(newLat, newLng);
                    // latlng.setPosition(latlng);
            }
            
            function makeMarrker(){
                multipleMarkers = []
                // console.log(markers)
                // for (i = 0; i <markers.length; i++) {
                        let markersr = new google.maps.Marker({
                            position:{lat:parseFloat(markers.lat),lng:parseFloat(markers.lng)},
                            map:map,
                            // animation: google.maps.Animation.DROP,
                            title:markers.driver,
                            draggable:false,
                            icon: icon
                        });
                        markersr.set('id', markers.id);
                        console.log(markersr)
                        
                        multipleMarkers.push(markersr);
                    // }
            }
            
            function DeleteMarkers() {
                //Loop through all the markers and remove
                for (var i = 0; i < multipleMarkers.length; i++) {
                    multipleMarkers[i].setMap(null);
                }
                markers = [];
            };
            initMap();
        })
</script>
@stack('js')
@toastr_js
@toastr_render
</body>
<!--end::Body-->
</html>
