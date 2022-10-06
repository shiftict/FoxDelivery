@extends('panel._layout')
@section('subTitle')
    @lang('navbar.createOrder')
@endsection

@push('style')

@endpush
@section('pageTitle')
    <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
    <span class="text-muted font-weight-bold mr-4">@lang('navbar.createOrder')</span>
    {{--<a href="#" class="btn btn-light-warning font-weight-bolder btn-sm">Add New</a>--}}
@endsection
@section('content')
    <!--begin::Row-->
    <div class="row">
        <div class="col-md-12">
            <!--begin::Card-->
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header">
                    <h3 class="card-title">
                        <span class="fas fa-shopping-cart icon-lg"></span>
                    </h3>

                </div>
                <!--begin::Form-->
                <form method="post" action="{{Route('order.set.driver', $order->id)}}">
                    @csrf
                    <input value="{{$order->id}}" name="order_id" hidden>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label>@lang('order.from_address')
                                    <span class="text-danger">*</span></label>
                                <input id="pac-input" disabled autocapitalize="off" autocomplete="off" value="{{$order->from_address}}" type="text" name="from_address" class="form-control @error('from_address') is-invalid @enderror" placeholder="@lang('order.from_addressDescription')" />
                                @if($errors->has('from_address'))
                                    <span class="text-danger">@lang('order.from_addressDescription')</span>
                                @else
                                    <span class="form-text text-muted">@lang('order.from_addressDescription').</span>
                                @endif
                            </div>
                            <div class="col-lg-4">
                                <label>@lang('order.to_address')
                                    <span class="text-danger">*</span></label>
                                <input id="pac-inputs" disabled autocapitalize="off" autocomplete="off" value="{{$order->to_address}}" type="text" name="to_address" class="form-control @error('to_address') is-invalid @enderror" placeholder="@lang('order.to_addressDescription')" />
                                @if($errors->has('to_address'))
                                    <span class="text-danger">@lang('order.to_addressDescription')</span>
                                @else
                                    <span class="form-text text-muted">@lang('order.to_addressDescription').</span>
                                @endif
                            </div>
                            <div class="col-lg-4">
                                <label for="exampleSelect1">@lang('order.driver')
                                    <span class="text-danger">*</span></label>
                                <select name="delivery_id" class="form-control @error('delivery_id') is-invalid @enderror" id="package">
                                    <option value="">--</option>
                                    @foreach(App\Models\Delivery::where('status', '1')->get() as $driver)
                                        <option value="{{$driver->id}}"><b>{{$driver->name}}</b></option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary mr-2">@lang('form.create')</button>
                        <button type="reset" class="btn btn-secondary">@lang('form.cancel')</button>
                    </div>
                </form>
                <!--end::Form-->
            </div>
            <!--end::Card-->
        </div>
    </div>
@endsection
