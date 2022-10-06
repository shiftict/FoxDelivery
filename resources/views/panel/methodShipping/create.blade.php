@extends('panel._layout')
@push('js')
    <!--begin::Page Scripts(used by this page)-->
{{--    <script src="{{asset('panel/validation/methodsShipping.js')}}"></script>--}}
<script>
    $( document ).ready(function() {
            $( "#hohose" ).click(function() {
              $('#hohose').attr('disabled', true);
              !$("#metod_shipping").submit();
            });
        });
</script>

@endpush
@section('subTitle')
    @lang('navbar.createMethods')
@endsection
@section('pageTitle')
    <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
    <span class="text-muted font-weight-bold mr-4">@lang('navbar.createMethods')</span>
    {{--<a href="#" class="btn btn-light-warning font-weight-bolder btn-sm">Add New</a>--}}
@endsection
@section('content')

                <div class="row">
                    <div class="col-md-12">
                        <!--begin::Card-->
                        <div class="card card-custom gutter-b example example-compact">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <span class="fas fa-shipping-fast"></span>
                                </h3>

                            </div>
                            <!--begin::Form-->
                            <form method="post" action="{{Route('method_shipping.store')}}" id="metod_shipping">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label>@lang('methodShipping.namear')
                                            <span class="text-danger">*</span></label>
                                            <input type="text" name="name[ar]" class="form-control @error('name.ar') is-invalid @enderror" placeholder="@lang('methodShipping.namePlaceholder')" />
                                            @if($errors->has('name.ar'))
                                                <span class="text-danger">@lang('methodShipping.validationName')</span>
                                            @endif
                                        </div>
                                        <div class="col-lg-4">
                                            <label>@lang('methodShipping.nameen')
                                                <span class="text-danger">*</span></label>
                                            <input type="text" name="name[en]" class="form-control @error('name.en') is-invalid @enderror" placeholder="@lang('methodShipping.namePlaceholder')" />

                                            @if($errors->has('name.en'))
                                                <span class="text-danger">@lang('methodShipping.validationName')</span>
                                            @endif
                                        </div>
                                        <div class="col-lg-2">
                                            <label>@lang('map.price')
                                                <span class="text-danger">*</span></label>
                                            <input type="text" name="price" class="form-control @error('price') is-invalid @enderror"/>
                                            @error('price')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2">
                                            <label for="exampleSelect1">@lang('form.status')
                                                <span class="text-danger">*</span></label>
                                            <select name="status" class="form-control @error('status') is-invalid @enderror" id="exampleSelect1">
                                                <option value=""></option>
                                                <option value="1">@lang('form.active')</option>
                                                <option value="0">@lang('form.inactive')</option>
                                            </select>
                                            @if($errors->has('status'))
                                                <span class="text-danger">@lang('methodShipping.validationStatus')</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="button" id="hohose" class="btn btn-primary mr-2">@lang('form.create')</button>
                                    <a href="{{Route('method_shipping.index')}}" class="btn btn-secondary">@lang('form.cancel')</a>
                                </div>
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Card-->
                    </div>
                </div>

@endsection
