@extends('panel._layout')
@section('subTitle')
    @lang('navbar.editDelivery')
@endsection
@section('pageTitle')
    <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
    <span class="text-muted font-weight-bold mr-4">@lang('navbar.editDelivery')</span>
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
                                    <span class="svg-icon svg-icon-dark svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo1\dist/../src/media/svg/icons\Communication\Add-user.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <polygon points="0 0 24 0 24 24 0 24"/>
        <path d="M18,8 L16,8 C15.4477153,8 15,7.55228475 15,7 C15,6.44771525 15.4477153,6 16,6 L18,6 L18,4 C18,3.44771525 18.4477153,3 19,3 C19.5522847,3 20,3.44771525 20,4 L20,6 L22,6 C22.5522847,6 23,6.44771525 23,7 C23,7.55228475 22.5522847,8 22,8 L20,8 L20,10 C20,10.5522847 19.5522847,11 19,11 C18.4477153,11 18,10.5522847 18,10 L18,8 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
        <path d="M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero"/>
    </g>
</svg><!--end::Svg Icon--></span>
                    </h3>

                </div>
                <!--begin::Form-->
                <form method="post" action="{{Route('delivery.update', $delivery->id)}}">
                    @csrf
                    @method('put')
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label>@lang('delivery.name')
                                    <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{$delivery->name}}" placeholder="@lang('delivery.namePlaceholder')" />
                                @if(!$errors->has('name'))
                                    <span class="form-text text-muted">@lang('delivery.descriptionOfName').</span>
                                @endif
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-4">
                                <label>@lang('delivery.phone')
                                    <span class="text-danger">*</span></label>
                                <div class="input-group ">
                                    <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <span class="svg-icon svg-icon-md"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo1\dist/../src/media/svg/icons\Devices\Phone.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                        <rect x="0" y="0" width="24" height="24"/>
                                                                        <path d="M7.13888889,4 L7.13888889,19 L16.8611111,19 L16.8611111,4 L7.13888889,4 Z M7.83333333,1 L16.1666667,1 C17.5729473,1 18.25,1.98121694 18.25,3.5 L18.25,20.5 C18.25,22.0187831 17.5729473,23 16.1666667,23 L7.83333333,23 C6.42705272,23 5.75,22.0187831 5.75,20.5 L5.75,3.5 C5.75,1.98121694 6.42705272,1 7.83333333,1 Z" fill="#000000" fill-rule="nonzero"/>
                                                                        <polygon fill="#000000" opacity="0.3" points="7 4 7 19 17 19 17 4"/>
                                                                        <circle fill="#000000" cx="12" cy="21" r="1"/>
                                                                    </g>
                                                                </svg><!--end::Svg Icon-->
                                                        </span>
                                                    </span>
                                    </div>
                                    <input type="text" value="{{$delivery->phone}}" class="form-control @error('phone') is-invalid @enderror" name="phone" placeholder="@lang('delivery.phonePlaceholder')" />
                                </div>
                                @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-4">
                                <label>@lang('store.password')
                                    <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="@lang('store.password')" />
                                
                                @error('password')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label for="exampleSelect1">@lang('delivery.shippingMethod')
                                    <span class="text-danger">*</span></label>
                                <select name="method_id" class="form-control @error('methodShipping') is-invalid @enderror" id="exampleSelect1">
                                    <option value=""></option>
                                    @foreach($methodsShippings as $key => $m)
                                        <option {{$delivery->method_id == $m->id ? 'selected' : '' }} value="{{$m->id}}">{{$m->name}}</option>
                                    @endforeach
                                </select>
                                @error('method_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label for="exampleSelect1">@lang('form.status')
                                    <span class="text-danger">*</span></label>
                                <select name="status" class="form-control @error('status') is-invalid @enderror" id="exampleSelect1">
                                    <option value=""></option>
                                    <option {{$delivery->status == '1' ? 'selected' : '' }} value="1">@lang('form.active')</option>
                                    <option {{$delivery->status == '0' ? 'selected' : '' }} value="0">@lang('form.inactive')</option>
                                </select>
                                @error('status')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary mr-2">@lang('form.update')</button>
                        <a href="{{Route('delivery.index')}}" class="btn btn-secondary">@lang('form.cancel')</a>
                    </div>
                </form>
                <!--end::Form-->
            </div>
            <!--end::Card-->
        </div>
    </div>
@endsection
