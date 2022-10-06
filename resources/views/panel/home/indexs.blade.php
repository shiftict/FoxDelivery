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
    
    @endif
    @if(Auth::user()->hasRole('vendor')) 
    
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
