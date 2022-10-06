@extends('panel._layout')
@section('subTitle')
    @lang('navbar.createStore')
@endsection
@push('js')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
    <script>
        $(function() {
            $("#kt_form_1").validate({
                rules: {
                    stock: {
                        required: true,
                        number: true,
                        minlength: 1,
                        min: 1,
                    },
                    pricing: {
                        required: true,
                        number: true,
                        minlength: 1,
                        min: 1,
                    },
                    start_package: {
                        required: true,
                    },
                    end_package: {
                        required: true,
                    },
                },
                messages: {
                    stock: {
                        required: '{!!__('package.require')!!}',
                        number: '{!!__('package.number')!!}',
                        minlength: '{{ __('package.min') }}',
                        min: '{{ __('package.min') }}',
                    },
                    pricing: {
                        required: '{!!__('package.require')!!}',
                        number: '{!!__('package.number')!!}',
                        minlength: '{{ __('package.min') }}',
                        min: '{{ __('package.min') }}',
                    },
                    start_package: {
                        required: '{!!__('package.require')!!}',
                    },
                    end_package: {
                        required: '{!!__('package.require')!!}',
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
        label#stock-error {
            color: red;
        }
        label#start_package-error {
            color: red;
        }
        label#end_package-error {
            color: red;
        }
        label#pricing-error {
            color: red;
        }
    </style>
@endpush
@section('pageTitle')
    <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
    <span class="text-muted font-weight-bold mr-4">@lang('package.edit_package')</span>
    {{--<a href="#" class="btn btn-light-warning font-weight-bolder btn-sm">Add New</a>--}}

    {{-- <script src="{{asset('panel/assets/js/pages/crud/forms/validation/form-controls.js')}}"></script> --}}
    <!--end::Global Theme Bundle-->

@endsection
@section('content')
    <!--begin::Row-->
    <div class="row" id="app">
        <div class="col-md-12">
            <!--begin::Card-->
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header">
                    <h3 class="card-title">
                        <span class="fas fa-gift icon-lg"></span>
                    </h3>

                </div>
                <!--begin::Form-->
                <form method="post" id="kt_form_1" action="{{Route('packages.order.edit', $id)}}">
                    @csrf
                    @method('put')
                    <div class="card-body">
                        <div id="stock" disabled="none" class="form-group row">
                            <div class="col-lg-6">
                                <label>@lang('store.stock')
                                    <span class="text-danger">*</span></label>
                                <input value="{{$pa->number_of_orders}}" name="stock" id="stock" autocomplete="off" type="text" class="form-control @error('stock') is-invalid @enderror" placeholder="@lang('store.stockPlaceholder')" />
                                @error('stock')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label>@lang('store.pricing') KWD
                                    <span class="text-danger">*</span></label>
                                <input value="{{$pa->price}}" name="pricing" id="pricing" autocomplete="off" type="text" class="form-control @error('pricing') is-invalid @enderror" placeholder="@lang('store.pricingPlaceholder')" />
                                @error('pricing')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label for="exampleSelect1">@lang('store.date')
                                    <span class="text-danger">*</span></label>
                                <div class="input-daterange input-group @error('startL_order') is-invalid @enderror" id="kt_datepicker_6">
                                    <input value="{{\Carbon\Carbon::parse($pa->start_at)->format('Y-m-d')}}" name="start_package" id="start_package" autocomplete="off" type="date" min='{{now()->format('Y-m-d')}}' class="form-control" />
                                    <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="la la-ellipsis-h"></i>
                                                                    </span>
                                    </div>
                                    <input value="{{\Carbon\Carbon::parse($pa->expired_at)->format('Y-m-d')}}" name="end_package" id="end_package" autocomplete="off" type="date" min='{{now()->format('Y-m-d')}}' class="form-control" />
                                </div>
                                @error('start_package')
                                    <span class="text-danger">{{$message}} <br /></span>
                                @enderror
                                @error('end_package')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary mr-2">@lang('form.update')</button>
                        <a href="{{Route('vendor.index')}}" class="btn btn-secondary">@lang('form.cancel')</a>
                    </div>
                </form>
                <!--end::Form-->
            </div>
            <!--end::Card-->
        </div>
    </div>
@endsection
