@extends('panel._layout')
@section('subTitle')
    @lang('navbar.newUser')
@endsection
@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
<script>
    $(function() {
        $("#kt_form_1").validate({
            rules: {
                 password: {
                    required: true,
                    minlength: 6,
                },
                password_confirm : {
                    required: true,
                    minlength : 6,
                    equalTo : "#password"
                }
            },
            messages: {
                password: {
                    required: '{!!__('auth.passwordRequired')!!}',
                    minlength: '{!!__('auth.passwordMin')!!}'
                },
                password_confirm: {
                    required: '{!!__('auth.passwordRequired')!!}',
                    minlength: '{!!__('auth.passwordMin')!!}',
                    equalTo: '{!!__('auth.passwordConfirm')!!}',
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
label#password-error {
    color: red;
}
label#password_confirm-error {
    color: red;
}
</style>
@endpush
@section('pageTitle')
    <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
    <span class="text-muted font-weight-bold mr-4">@lang('sidebar.setting')</span>
    {{--<a href="#" class="btn btn-light-warning font-weight-bolder btn-sm">Add New</a>--}}
@endsection
@section('content')

    <!--begin::Entry-->
	<div class="d-flex flex-column-fluid">
							<!--begin::Container-->
							<div class="container">
								<!--begin::Row-->
								<div class="row">
								    <div class="@if($packages->number_of_order){{'col-xl-6'}} @else{{'col-xl-4'}} @endif">
										<!--begin::List Widget 1-->
										<div class="card card-custom card-stretch gutter-b">
											<!--begin::Header-->
											<div class="card-header border-0 pt-5">
												<h3 class="card-title align-items-start flex-column">
													<span class="card-label font-weight-bolder text-dark">@lang('sidebar.area')</span>
													<span class="text-muted mt-3 font-weight-bold font-size-sm"></span>
												</h3>
											</div>
											<!--end::Header-->
											<!--begin::Body-->
											<div class="card-body pt-8">
											    @foreach($city as $c)
    												<!--begin::Item-->
    												<div class="d-flex align-items-center mb-10">
    													<!--begin::Symbol-->
    													<div class="symbol symbol-40 symbol-light-primary mr-5">
    														<span class="symbol-label">


    															<span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Map\Marker1.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                        <rect x="0" y="0" width="24" height="24"/>
                                                                        <path d="M5,10.5 C5,6 8,3 12.5,3 C17,3 20,6.75 20,10.5 C20,12.8325623 17.8236613,16.03566 13.470984,20.1092932 C12.9154018,20.6292577 12.0585054,20.6508331 11.4774555,20.1594925 C7.15915182,16.5078313 5,13.2880005 5,10.5 Z M12.5,12 C13.8807119,12 15,10.8807119 15,9.5 C15,8.11928813 13.8807119,7 12.5,7 C11.1192881,7 10,8.11928813 10,9.5 C10,10.8807119 11.1192881,12 12.5,12 Z" fill="#000000" fill-rule="nonzero"/>
                                                                    </g>
                                                                </svg><!--end::Svg Icon--></span>
    														</span>
    													</div>
    													<!--end::Symbol-->
    													<!--begin::Text-->
    													<div class="d-flex flex-column font-weight-bold">
    														<a href="#" class="text-dark text-hover-primary mb-1 font-size-lg">{{$c->name}}</a>
    														<span class="text-muted">{{$c->area->name}}</span>
    													</div>
    													<!--end::Text-->
    												</div>
    												<!--end::Item-->
    											@endforeach
											</div>
											<!--end::Body-->
										</div>
										<!--end::List Widget 1-->
									</div>
									<div class="@if($packages->number_of_order){{'col-xl-6'}} @else{{'col-xl-4'}} @endif">
										<!--begin::List Widget 1-->
										<div class="card card-custom card-stretch gutter-b">
											<!--begin::Header-->
											<div class="card-header border-0 pt-5">
												<h3 class="card-title align-items-start flex-column">
													<span class="card-label font-weight-bolder text-dark">@lang('package.package')</span>
													<span class="text-muted mt-3 font-weight-bold font-size-sm"></span>
												</h3>
											</div>
											<!--end::Header-->
											<!--begin::Body-->
											<div class="card-body pt-8">
											    @foreach($packagesWithExpierd as $p)
    												<!--begin::Item-->
    												<div class="d-flex align-items-center mb-10">
    													<!--begin::Symbol-->
    													<div class="symbol symbol-40 symbol-light-primary mr-5">
    														<span class="symbol-label">


                                                                @if($p->status == '1')
                                                                <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Navigation\Check.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                        <polygon points="0 0 24 0 24 24 0 24"/>
                                                                        <path d="M6.26193932,17.6476484 C5.90425297,18.0684559 5.27315905,18.1196257 4.85235158,17.7619393 C4.43154411,17.404253 4.38037434,16.773159 4.73806068,16.3523516 L13.2380607,6.35235158 C13.6013618,5.92493855 14.2451015,5.87991302 14.6643638,6.25259068 L19.1643638,10.2525907 C19.5771466,10.6195087 19.6143273,11.2515811 19.2474093,11.6643638 C18.8804913,12.0771466 18.2484189,12.1143273 17.8356362,11.7474093 L14.0997854,8.42665306 L6.26193932,17.6476484 Z" fill="#000000" fill-rule="nonzero" transform="translate(11.999995, 12.000002) rotate(-180.000000) translate(-11.999995, -12.000002) "/>
                                                                    </g>
                                                                </svg><!--end::Svg Icon--></span>
                                                                @else
                                                                <span class="svg-icon svg-icon-danger svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Navigation\Close.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                        <g transform="translate(12.000000, 12.000000) rotate(-45.000000) translate(-12.000000, -12.000000) translate(4.000000, 4.000000)" fill="#000000">
                                                                            <rect x="0" y="7" width="16" height="2" rx="1"/>
                                                                            <rect opacity="0.3" transform="translate(8.000000, 8.000000) rotate(-270.000000) translate(-8.000000, -8.000000) " x="0" y="7" width="16" height="2" rx="1"/>
                                                                        </g>
                                                                    </g>
                                                                </svg><!--end::Svg Icon--></span>
                                                                @endif
    														</span>
    													</div>
    													<!--end::Symbol-->
    													<!--begin::Text-->
    													<div class="d-flex flex-column font-weight-bold">
    														<a href="#" class="text-dark text-hover-primary mb-1 font-size-lg">{{$p->pricing}} KD</a>
    														<span class="text-muted">{{$p->number_of_order ? $p->number_of_order . ' - ' . __('order.orderStay') : \Carbon\Carbon::parse($p->date_from)->diffInDays(\Carbon\Carbon::parse($p->date_to)) . ' - ' . __('store.orderDay')}} </span>
    													</div>
    													<!--end::Text-->
    												</div>
    												<!--end::Item-->
    											@endforeach
											</div>
											<!--end::Body-->
										</div>
										<!--end::List Widget 1-->
									</div>
{{--                                    @if(!$packages->number_of_order)--}}
{{--                                        <div class="col-xl-4">--}}
{{--                                            <!--begin::List Widget 1-->--}}
{{--                                            <div class="card card-custom card-stretch gutter-b">--}}
{{--                                                <!--begin::Header-->--}}
{{--                                                <div class="card-header border-0 pt-5">--}}
{{--                                                    <h3 class="card-title align-items-start flex-column">--}}
{{--                                                        <span class="card-label font-weight-bolder text-dark">@lang('sidebar.delivery')</span>--}}
{{--                                                        <span class="text-muted mt-3 font-weight-bold font-size-sm"></span>--}}
{{--                                                    </h3>--}}
{{--                                                </div>--}}
{{--                                                <!--end::Header-->--}}
{{--                                                <!--begin::Body-->--}}
{{--                                                <div class="card-body pt-8">--}}
{{--                                                @foreach($packages->driver as $d)--}}
{{--                                                    <!--begin::Item-->--}}
{{--                                                        <div class="d-flex align-items-center mb-10">--}}
{{--                                                            <!--begin::Symbol-->--}}
{{--                                                            <div class="symbol symbol-40 symbol-light-primary mr-5">--}}
{{--    														<span class="symbol-label">--}}
{{--    															<i class="fas fa-shipping-fast text-success"></i>--}}
{{--    														</span>--}}
{{--                                                            </div>--}}
{{--                                                            <!--end::Symbol-->--}}
{{--                                                            <!--begin::Text-->--}}
{{--                                                            <div class="d-flex flex-column font-weight-bold">--}}
{{--                                                                <a href="#" class="text-dark text-hover-primary mb-1 font-size-lg">{{$d->Driver->name}}</a>--}}
{{--                                                                <span class="text-muted"><i class="far fa-clock"></i> {{$d->time_from}} - {{$d->time_to}}</span>--}}
{{--                                                            </div>--}}
{{--                                                            <!--end::Text-->--}}
{{--                                                        </div>--}}
{{--                                                        <!--end::Item-->--}}
{{--                                                    @endforeach--}}
{{--                                                </div>--}}
{{--                                                <!--end::Body-->--}}
{{--                                            </div>--}}
{{--                                            <!--end::List Widget 1-->--}}
{{--                                        </div>--}}
{{--                                    @endif--}}
								</div>
							</div>
						</div>

    <!--begin::Row-->
    <div class="row">
        <div class="col-md-12">
            <!--begin::Card-->
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header">
                    <h3 class="card-title">
                        <span class="fas fa-lock icon-lg"></span>
                    </h3>

                </div>
                <!--begin::Form-->
                <form id="kt_form_1" method="post" action="{{Route('vendor.update.password')}}">
                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label>@lang('users.password')
                                    <span class="text-danger">*</span></label>

                                    <input autocomplete="off" id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="@lang('users.passwordPlaceholder')" />

                                @error('password')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-4">
                                <label>@lang('auth.passwordConfirmForm')
                                    <span class="text-danger">*</span></label>
                                    <input autocomplete="off" type="password" class="form-control @error('password_confirm') is-invalid @enderror" name="password_confirm" placeholder="@lang('users.passwordPlaceholder')" />

                                @error('password_confirm')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary mr-2">@lang('form.update')</button>
                    </div>
                </form>
                <!--end::Form-->
            </div>
            <!--end::Card-->
        </div>
    </div>
@endsection
