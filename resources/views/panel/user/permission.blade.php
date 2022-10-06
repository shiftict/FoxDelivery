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
    <span class="text-muted font-weight-bold mr-4">@lang('navbar.permission')</span>
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

                <form method="post" action="{{Route('users.update.permission')}}">
                    @csrf
                    <input hidden value="{{$id}}" name="user">
                    <div class="card-body">

                        <div class="form-group row">
                            <div class="col-lg-12">
                                <h3><i class="fas fa-user-tie"></i> @lang('role.management')</h3>
                            </div>

                            <br/>

                            @foreach($role as $r)
                                <div class="col-lg-4">
                                    <label>{{$r->display_name}} </label>

                                    <div class="checkbox-inline">
                                        <label class="checkbox">
                                            <input class="role" type="checkbox" data-id="{{$r->id}}"
                                                   {{ $user->hasRole($r->name) ? 'checked' : '' }} name="role[{{$r->id}}]"
                                                   value="{{$r->name}}">
                                            <span></span>{{$r->display_name}}</label>
                                    </div>

{{--                                    <span class="form-text text-muted">{{$r->display_name}}</span>--}}
                                    <div class="separator separator-dashed my-10"></div>
                                </div>

                            @endforeach
                        </div>
                        <div class="separator separator-dashed my-10"></div>
                        <div class="col-lg-12">
                            <h3>
                                <div class="checkbox-inline">
                                    <label class="checkbox">
{{--                                        <input type="checkbox" id="permissionParent" name="permissionParent" >--}}
{{--                                        <span></span>--}}
                                        <i class="fas fa-user-lock"></i> @lang('role.permission')
                                    </label>
                                </div>
                                </h3>
                        </div>
                        <br/>
                        <div class="form-group row">
                            @foreach($permission as $p)
                                <div class="col-lg-4">
                                    <label>{{$p->display_name}}</label>

                                    <div class="checkbox-inline">
                                        <label class="checkbox">
                                             <input
                                                class="permission @foreach($p->roles->pluck('id')->toArray() as $role_id) {{'role_'.$role_id}} @endforeach"
                                                type="checkbox"
                                                {{ in_array($p->id,$user->permissions->pluck('id')->toArray()) ? 'checked' : '' }}  name="permission[]"
                                                value="{{$p->id}}">
                                            <span></span>{{$p->display_name}}</label>
                                    </div>

{{--                                    <span class="form-text text-muted">{{$p->display_name}}</span>--}}
                                    <div class="separator separator-dashed my-10"></div>
                                </div>

                            @endforeach
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary mr-2">@lang('form.create')</button>
                        <a href="{{Route('users.store')}}" class="btn btn-secondary">@lang('form.cancel')</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $('input.role').click(function () {
            var role_id = $(this).data('id');
            $("input.role_" + role_id + ":checkbox").prop('checked', $(this).prop("checked"));
        })
    </script>
@endpush
