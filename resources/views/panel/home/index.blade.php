@extends('panel._layout')
@push('style')
    <style>
    /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
#map {
  height: 100%;
}

/* Optional: Makes the sample page fill the window. */
html,
body {
  height: 100%;
  margin: 0;
  padding: 0;
}
</style>
@endpush
@push('js')
  
    <script
        src="{{"https://maps.googleapis.com/maps/api/js?key=AIzaSyDQDC3VV5CGRaueUYpEEJ308KNx8zbG5t0&language=".app()->getLocale()."&callback=initMap&v=weekly"}}"
        type="text/javascript">
    </script>
    <script>
    @if(Auth::user()->hasRole('superadministrator') || Auth::user()->hasRole('administrator'))
    jQuery(document).ready(function() {
            let map;
            // let pinsLocation = {!! json_encode($drivers_collection) !!};
            let multipleMarkers = [];
            var markers = {!! json_encode($drivers_collection) !!};
            
            
            setInterval(function() {
           $.ajax({
                      url: '{!!route('map.driver')!!}',
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
                        markers = response.delivery
                        $.each(response.delivery, function(k, v) {
                            updatePositionOfSpecificMarker(v.id, v.lat, v.lng)
                            
                        // makeMarrker(v.id, v.name, v.lat, v.lng)
                            // markers.push(v)
                        });
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
                for(let i=0; i < markers.length;i++){
                //console.log(markers[0]);
                     var marker = new google.maps.Marker({
                        position:{lat:parseFloat(markers[i].lat),lng:parseFloat(markers[i].lng)},
                        map:map,
                        animation: google.maps.Animation.DROP,
                        title:markers[i].name,
                        draggable:false,
                        icon: icon
                    });
                    
                    marker.set('id', markers[i].id);
                    multipleMarkers.push(marker);
                }
            }
            
            function updatePositionOfSpecificMarker(id, newLat, newLng)
            {
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
                console.log(markers)
                for (i = 0; i <markers.length; i++) {
                        let markersr = new google.maps.Marker({
                            position:{lat:parseFloat(markers[i].lat),lng:parseFloat(markers[i].lng)},
                            map:map,
                            // animation: google.maps.Animation.DROP,
                            title:markers[i].name,
                            draggable:false,
                            icon: icon
                        });
                        markersr.set('id', markers[i].id);
                        console.log(markersr)
                        
                        multipleMarkers.push(markersr);
                    }
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
    @endif
    @if(Auth::user()->hasRole('vendor')) 
    jQuery(document).ready(function() {
            let map;
            // let pinsLocation = {!! json_encode($drivers_collection) !!};
            let multipleMarkers = [];
            var markers = {!! json_encode($drivers_collection) !!};
           
            setInterval(function() {
                    $.ajax({
                          url: '{!!route('map.my.driver.vendors')!!}',
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
                            markers = response.delivery
                            $.each(response.delivery, function(k, v) {
                                var dt = new Date();
                                var start_first_shfit = new Date((dt.getMonth() + 1) + "/" + dt.getDate() + "/" + dt.getFullYear() + " " + v.start_first_shift);
                                var end_first_shfit = new Date((dt.getMonth() + 1) + "/" + dt.getDate() + "/" + dt.getFullYear() + " " + v.finish_first_shift);
                                var start_secound_shfit = new Date((dt.getMonth() + 1) + "/" + dt.getDate() + "/" + dt.getFullYear() + " " + v.start_secound_shift);
                                var end_secound_shfit = new Date((dt.getMonth() + 1) + "/" + dt.getDate() + "/" + dt.getFullYear() + " " + v.finish_secound_shift);
                                // console.log(start_first_shfit.getTime())
                                console.log('===================+++=======================')
                                // console.log(dt.getTime())
                                if((dt.getTime() > start_first_shfit.getTime() && dt.getTime() < end_first_shfit.getTime()) || (dt.getTime() > start_secound_shfit.getTime() && dt.getTime() < end_secound_shfit.getTime())) {
                                    updatePositionOfSpecificMarker(v.id, v.lat, v.lng)
                                }
                            // makeMarrker(v.id, v.name, v.lat, v.lng)
                                // markers.push(v)
                            });
                            // console.log(response)
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
                for(let i=0; i < markers.length;i++){
                                var dt = new Date();
                                var start_first_shfit = new Date((dt.getMonth() + 1) + "/" + dt.getDate() + "/" + dt.getFullYear() + " " + markers[i].start_first_shift);
                                var end_first_shfit = new Date((dt.getMonth() + 1) + "/" + dt.getDate() + "/" + dt.getFullYear() + " " + markers[i].finish_first_shift);
                                var start_secound_shfit = new Date((dt.getMonth() + 1) + "/" + dt.getDate() + "/" + dt.getFullYear() + " " + markers[i].start_secound_shift);
                                var end_secound_shfit = new Date((dt.getMonth() + 1) + "/" + dt.getDate() + "/" + dt.getFullYear() + " " + markers[i].finish_secound_shift);
                                if((dt.getTime() < start_first_shfit.getTime() && dt.getTime() > end_first_shfit.getTime()) || (dt.getTime() < start_secound_shfit.getTime() && dt.getTime() > end_secound_shfit.getTime())) {
                                    var marker = new google.maps.Marker({
                                        position:{lat:parseFloat(markers[i].lat),lng:parseFloat(markers[i].lng)},
                                        map:map,
                                        animation: google.maps.Animation.DROP,
                                        title:markers[i].name,
                                        draggable:false,
                                        icon: icon
                                    });
                                    
                                    marker.set('id', markers[i].id);
                                    multipleMarkers.push(marker);
                                }
                     
                }
            }
            
            function updatePositionOfSpecificMarker(id, newLat, newLng)
            {
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
                
                
                for(let i=0; i < markers.length;i++){
                                var dt = new Date();
                                var start_first_shfit = new Date((dt.getMonth() + 1) + "/" + dt.getDate() + "/" + dt.getFullYear() + " " + markers[i].start_first_shift);
                                var end_first_shfit = new Date((dt.getMonth() + 1) + "/" + dt.getDate() + "/" + dt.getFullYear() + " " + markers[i].finish_first_shift);
                                var start_secound_shfit = new Date((dt.getMonth() + 1) + "/" + dt.getDate() + "/" + dt.getFullYear() + " " + markers[i].start_secound_shift);
                                var end_secound_shfit = new Date((dt.getMonth() + 1) + "/" + dt.getDate() + "/" + dt.getFullYear() + " " + markers[i].finish_secound_shift);
                                if((dt.getTime() < start_first_shfit.getTime() && dt.getTime() > end_first_shfit.getTime()) || (dt.getTime() < start_secound_shfit.getTime() && dt.getTime() > end_secound_shfit.getTime())) {
                                    let markersr = new google.maps.Marker({
                                        position:{lat:parseFloat(markers[i].lat),lng:parseFloat(markers[i].lng)},
                                        map:map,
                                        // animation: google.maps.Animation.DROP,
                                        title:markers[i].name,
                                        draggable:false,
                                        icon: icon
                                    });
                                    markersr.set('id', markers[i].id);
                                    // console.log(markersr)
                                    
                                    multipleMarkers.push(markersr);
                                }
                     
                }
                
                // for (i = 0; i <markers.length; i++) {
                //         let markersr = new google.maps.Marker({
                //             position:{lat:parseFloat(markers[i].lat),lng:parseFloat(markers[i].lng)},
                //             map:map,
                //             // animation: google.maps.Animation.DROP,
                //             title:markers[i].name,
                //             draggable:false,
                //             icon: icon
                //         });
                //         markersr.set('id', markers[i].id);
                //         // console.log(markersr)
                        
                //         multipleMarkers.push(markersr);
                //     }
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
    @endif
        
    </script>
@endpush
@section('content')
<div id="root"></div>
    @if(Auth::user()->hasRole('superadministrator') || Auth::user()->hasRole('administrator'))
        @include('panel.bord.admin')
    @elseif(Auth::user()->hasRole('vendor'))
        @include('panel.bord.vendor')
    @endif
    <!--begin::Row-->
        @if(Auth::user()->hasRole('superadministrator') || Auth::user()->hasRole('administrator'))
        <div class="card card-custom gutter-b">
            <div class="card-header card-header-tabs-line">
                <div class="card-toolbar">
                    <ul class="nav nav-tabs nav-bold nav-tabs-line">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#kt_tab_pane_1_4">
                                <span class="nav-icon"><i class="flaticon2-shopping-cart"></i></span>
                                <span class="nav-text">@lang('navbar.order')</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#kt_tab_pane_2_4">
                                <span class="nav-icon"><i class="flaticon-map-location"></i></span>
                                <span class="nav-text">@lang('navbar.map')</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="kt_tab_pane_1_4" role="tabpanel" aria-labelledby="kt_tab_pane_1_4">
                        @include('panel.home.admin.orderStatus')

                    </div>
                    <div class="tab-pane fade" id="kt_tab_pane_2_4" role="tabpanel" aria-labelledby="kt_tab_pane_2_4">
                        
                        @include('panel.home.admin.map')
                    </div>
                </div>
            </div>
        </div>

        @elseif(Auth::user()->hasRole('vendor'))
            <div class="card card-custom gutter-b">
                <div class="card-header card-header-tabs-line">
                    <div class="card-toolbar">
                        <ul class="nav nav-tabs nav-bold nav-tabs-line">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#kt_tab_pane_1_4">
                                    <span class="nav-icon"><i class="flaticon2-shopping-cart"></i></span>
                                    <span class="nav-text">@lang('navbar.order')</span>
                                </a>
                            </li>
                            @if(Auth::user()->package_hours)
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#kt_tab_pane_2_4">
                                        <span class="nav-icon"><i class="flaticon-map-location"></i></span>
                                        <span class="nav-text">@lang('navbar.map')</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="kt_tab_pane_1_4" role="tabpanel" aria-labelledby="kt_tab_pane_1_4">
                            @include('panel.home.vendor.orderStatus')
                        </div>
                        <div class="tab-pane fade" id="kt_tab_pane_2_4" role="tabpanel" aria-labelledby="kt_tab_pane_2_4">
                            @include('panel.home.vendor.map')
                        </div>
                    </div>
                </div>
            </div>
        @endif
    <!--end::Row-->
@endsection
