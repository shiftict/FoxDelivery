@extends('panel._layout')
@section('subTitle')
    @lang('navbar.createStore')
@endsection
@push('js')
    {{--    <script src="{{asset('panel/assets/js/pages/crud/forms/widgets/bootstrap-timepicker.js')}}"></script>--}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
        <script>
            $(function() {
            $("#kt_form_1").validate({
                rules: {
                    stok: {
                        required: true,
                        min: 1,
                    },
                    pricing: {
                        required: true,
                        min: 1,
                    },
                },
                messages: {
                    stok: {
                        required: '{!!__('package.require')!!}',
                        min: '{!!__('package.min')!!}'
                    },
                    pricing: {
                        required: '{!!__('package.require')!!}',
                        min: '{!!__('package.min')!!}',
                    },
                },
                submitHandler: function(form) {
                    // form.submit();
                    // $('#submitFrom').hidden()
                    $( "#submitFrom" ).prop( "disabled", true );
                    form.submit();
                    //$( "#submitFrom" ).prop( "disabled", true );
                    // console.log('aaa')
                    // $( "#sends" ).prop( "disabled", false );
                }
            });
        });
    </script>
@endpush
@push('style')
    <style>
        label#stok-error {
            color: red;
        }
        label#pricing-error {
            color: red;
        }
    </style>
@endpush
@section('pageTitle')
    <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
    <span class="text-muted font-weight-bold mr-4">@lang('store.newPackage')</span>
    {{--<a href="#" class="btn btn-light-warning font-weight-bolder btn-sm">Add New</a>--}}
@endsection
@section('content')
    <!--begin::Row-->
    <div class="row" id="app">
        <div class="col-md-12">
            <!--begin::Card-->
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header">
                    <h3 class="card-title">
                        <span class="fab fa-sketch icon-lg"></span>
                    </h3>

                </div>
                <!--begin::Form-->
                <form method="post" id="kt_form_1" action="{{Route('packages.rnewPackage.post.user')}}">
                    @csrf
                    <input name="id_user" type="hidden" value="{{$id}}">
                    <div class="card-body">
                        @lang('store.packagePerOrders')
                        @error('id_user')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                        <div id="stockHr" disabled="none" class="separator separator-dashed my-10"></div>
                        <div id="stock" disabled="none" class="form-group row">
                            <div class="col-lg-6">
                                <label>@lang('store.stock')
                                    <span class="text-danger">*</span></label>
                                    <input value="{{old('stok')}}" name="stok" autocomplete="off" type="number" class="form-control @error('stock') is-invalid @enderror" placeholder="@lang('store.stockPlaceholder')" />
                                @error('stok')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col-lg-6 mb-9">
                                <label>@lang('store.pricing')
                                    <span class="text-danger">*</span></label>
                                    <input value="{{old('pricing')}}" name="pricing" autocomplete="off" type="number" class="form-control @error('pricing') is-invalid @enderror" placeholder="@lang('store.pricingPlaceholder')" />
                                @error('pricing')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label for="exampleSelect1">@lang('store.date')
                                    <span class="text-danger">*</span></label>
                                <div class="input-daterange input-group @error('startL_order') is-invalid @enderror" id="kt_datepicker_6">
                                    <input value="{{old('start')}}" name="start" autocomplete="off" type="date" min='{{now()->format('Y-m-d')}}' class="form-control" />
                                    <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="la la-ellipsis-h"></i>
                                                                    </span>
                                    </div>
                                    <input value="{{old('end')}}" autocomplete="off" name="end" type="date" min='{{now()->format('Y-m-d')}}' class="form-control" />
                                </div>
                                @error('start')
                                <span class="text-danger">{{$message}}</span> &nbsp;
                                @enderror
                                @error('end')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
{{--                                @if ($errors->has('start') && $errors->has('end'))--}}
{{--                                    <span class="text-danger">{{ $errors->first('start')}}</span> &nbsp;--}}
{{--                                @endif--}}
{{--                                @if ($errors->has('start') && !$errors->has('end'))--}}
{{--                                    <span class="text-danger">{{ $errors->first('start')}}</span> &nbsp;--}}
{{--                                @endif--}}
{{--                                @if (!$errors->has('start') && $errors->has('end'))--}}
{{--                                    <span class="text-danger">{{ $errors->first('end')}}</span> &nbsp;--}}
{{--                                @endif--}}
                            </div>
                            <div class="col-lg-6">


                                <div class="form-group row">
                                    <div class="col-9 col-form-label">
                                        <div class="checkbox-inline">

                                            <label class="checkbox checkbox-outline checkbox-success">
                                                <input type="checkbox" name="blance">
                                                <span></span></label>
                                        </div>
                                        <span class="form-text text-muted">@lang('package.sentPackageBlance')</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button id="submitFrom" type="submit" class="btn btn-primary mr-2">@lang('form.create')</button>
                        <a href="{{Route('vendor.index')}}" class="btn btn-secondary">@lang('form.cancel')</a>
                    </div>
                </form>
                <!--end::Form-->
            </div>
            <!--end::Card-->
        </div>
    </div>
@endsection
