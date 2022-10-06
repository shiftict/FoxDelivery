@extends('panel._layout')
@section('subTitle')
    @lang('navbar.permission')
@endsection
@push('js')
    <script>
        $( document ).ready(function() {
            $( "#roleParent" ).on( "click", function () {
                if($('#roleParent').is(':checked') === true) {
                    $("#roleChilde").prop("checked", true)
                } else {
                    $("#roleChilde").prop("checked", false)
                }
            });

            $( "#permissionParent" ).on( "click", function () {
                if($('#permissionParent').is(':checked') === true) {
                    console.log('aaaaaaaa')
                    $("#permissionChilde").prop("checked", true)
                } else {
                    console.log('bbbbbbb')
                    $("#permissionChilde").prop("checked", false)
                }
            });
        });
    </script>
@endpush
@section('pageTitle')
    <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
    <span class="text-muted font-weight-bold mr-4">@lang('navbar.city')</span>
    {{--<a href="#" class="btn btn-light-warning font-weight-bolder btn-sm">Add New</a>--}}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header">
                    <h3 class="card-title">
                        <span class="svg-icon svg-icon-dark svg-icon-2x">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                 width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <mask fill="white">
                                        <use xlink:href="#path-1"/>
                                    </mask>
                                    <g/>
                                    <path
                                        d="M15.6274517,4.55882251 L14.4693753,6.2959371 C13.9280401,5.51296885 13.0239252,5 12,5 C10.3431458,5 9,6.34314575 9,8 L9,10 L14,10 L17,10 L18,10 C19.1045695,10 20,10.8954305 20,12 L20,18 C20,19.1045695 19.1045695,20 18,20 L6,20 C4.8954305,20 4,19.1045695 4,18 L4,12 C4,10.8954305 4.8954305,10 6,10 L7,10 L7,8 C7,5.23857625 9.23857625,3 12,3 C13.4280904,3 14.7163444,3.59871093 15.6274517,4.55882251 Z"
                                        fill="#000000"/>
                                </g>
                            </svg>
                        </span>
                    </h3>
                </div>

                <form method="post" action="{{Route('vendor.city.add')}}">
                    @csrf
                    <input hidden value="{{$id}}" name="user">
                    <input hidden value="{{$vendor->user->id}}" name="user_id">
                    <div class="card-body">

                            @foreach($area as $r)
                        <div class="form-group permission-role row">
                                @php
                                    $city = App\Models\City::where('status', '1')->where('area_id', $r->id)->get();
                                @endphp
                                <div class="col-lg-12">

                                    <div class="checkbox-inline">
                                        <label class="checkbox">
                                            <input class="role" type="checkbox" data-id="{{$r->id}}">
                                            <span></span><b style="font-size: large;">{{$r->name}}</b></label>
                                    </div>
                                    <div class="form-group row">
                            @foreach($city as $p)
                                <div class="col-lg-12">

                                    <div class="checkbox-inline">
                                        <label class="checkbox">
                                             <input name="city[]"
                                             value="{{$p->id}}"
                                                class="permission {{'role_'.$r->id}}"
                                                {{ in_array($p->id,$vendor->user->city->pluck('city_id')->toArray()) ? 'checked' : '' }}
                                                type="checkbox">
                                            <span></span>{{$p->name}}</label>
                                    </div>
                                </div>

                            @endforeach
                        </div>
                                    <div class="separator separator-dashed my-10"></div>
                                </div>

                        </div>
                        @endforeach
                        <br/>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary mr-2">@lang('form.create')</button>
                        <a href="{{Route('vendor.index')}}" class="btn btn-secondary">@lang('form.cancel')</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
    $( document ).ready(function() {
        let per = $('input.permission').parents('.permission-role').find('input.permission:checked')
        // console.log(per)
    });
        $('input.role').click(function () {
            var role_id = $(this).data('id');
            console.log(role_id)
            $("input.role_" + role_id + ":checkbox").prop('checked', $(this).prop("checked"));
        })
    </script>
@endpush
