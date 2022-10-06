@extends('panel._layout')
@section('subTitle')
    @lang('navbar.createArea')
@endsection
@push('js')
    <script async defer
            src="{{"https://maps.googleapis.com/maps/api/js?key=AIzaSyDQDC3VV5CGRaueUYpEEJ308KNx8zbG5t0&language=".app()->getLocale()."&libraries=places&callback=initMap"}}"
            type="text/javascript">
    </script>
    <!-- Async script executes immediately and must be after any DOM elements used in callback. -->
    <script type="text/javascript">
        function initMap() {
            $("#latLocation").val("29.33744230597737");
            $("#longLocation").val("48.022533337402336");
            // init location
            var gaza = {lat: 29.33744230597737, lng: 48.022533337402336};
            // The map, centered init location
            var map = new google.maps.Map(
                document.getElementById('map'), {zoom: 12, center: gaza});
            // The marker, positioned init location
            var marker = new google.maps.Marker({position: gaza, map: map, draggable: true});

            google.maps.event.addListener(marker, 'dragend', function(markerEvent) {//dragstart - drag - dragend
                $("#latLocation").val(markerEvent.latLng.lat());
                $("#longLocation").val(markerEvent.latLng.lng());
            })
        }
        
        $( document ).ready(function() {
            $( "#hohose" ).click(function() {
              $('#hohose').attr('disabled', true);
              !$("#form_area").submit();
            });
        });
    </script>
@endpush
@section('pageTitle')
    <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
    <span class="text-muted font-weight-bold mr-4">@lang('navbar.createArea')</span>
    {{--<a href="#" class="btn btn-light-warning font-weight-bolder btn-sm">Add New</a>--}}
@endsection
@section('content')
    <!--begin::Row-->
    <div class="row">
        <div class="col-md-12">
            <form method="post" action="{{Route('area.store')}}" id="form_area">
                @csrf
                <div class="col-lg-12 col-sm-12">
                    <div class="form-group row">
                        <div class="col-lg-2">
                            <label>@lang('area.nameOfAreaAr')
                                <span class="text-danger">*</span></label>
                            <input type="text" name="name[ar]"
                                   class="form-control @error('name.ar') is-invalid @enderror" placeholder="@lang('area.nameOfAreaPlaceholder')"/>
                            @error('name.ar')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-lg-2">
                            <label>@lang('area.nameOfAreaEn')
                                <span class="text-danger">*</span></label>
                            <input type="text" name="name[en]"
                                   class="form-control @error('name.en') is-invalid @enderror" placeholder="@lang('area.nameOfAreaPlaceholder')"/>
                            @error('name.en')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-lg-2">
                            <label>@lang('map.lat')</label>
                            <input type="text" name="lat" id="latLocation"
                                   class="form-control @error('lat') is-invalid @enderror" value="{{old('lat')}}"/>
                            @error('lat')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-lg-2">
                            <label>@lang('map.long')</label>
                            <input type="text" name="long" id="longLocation"
                                   class="form-control @error('long') is-invalid @enderror" value="{{old('long')}}"/>
                            @error('long')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-lg-4">
                            <label>@lang('form.status')
                                <span class="text-danger">*</span></label>
                            <select name="status" class="form-control @error('status') is-invalid @enderror" id="exampleSelect1">
                                <option value=""></option>
                                <option value="1">@lang('form.active')</option>
                                <option value="0">@lang('form.inactive')</option>
                            </select>
                            @error('status')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <button id="hohose" class="btn btn-primary mb-5" type="button">@lang('form.create')</button>
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
