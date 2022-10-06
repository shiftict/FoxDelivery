@extends('panel._layout')
@push('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-selection__rendered {
    line-height: 40px !important;
}
.select2-container .select2-selection--single {
    height: 40px !important;
}
.select2-selection__arrow {
    height: 38px !important;
}
    </style>
@endpush
@push('js')

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $( document ).ready(function() {
            $( "#xls_report" ).click(function() {
              $('#xls_report').attr('disabled', true);
              !$("#submit_filter_w").submit();
            });
        });
        
        $('#exampleSelect1').select2({
                    placeholder: "{{__('sidebar.vendor')}}",
                    theme: "classic",
                    width: 'resolve',
                    allowClear: true
                });
    </script>
@endpush
@section('subTitle')
    @lang('sidebar.vendor')
@endsection
@section('pageTitle')
    <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
    <span class="text-muted font-weight-bold mr-4">@lang('sidebar.reportVendors')</span>
    {{--<a href="#" class="btn btn-light-warning font-weight-bolder btn-sm">Add New</a>--}}
@endsection
@section('content')
    <!--begin::Row-->
    <div class="card card-custom">
        <!--begin::Header-->
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">
                    <i class="fa la-store icon-lg"></i>
                </h3>
            </div>
            <div class="card-toolbar">
                <!--begin::Dropdown-->
                <!--<button style="margin: 0 10px 0 10px" id="btn_show_search_box" onclick="check_collapse()" class="btn btn-light-primary font-weight-bolder" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">-->
                <!--    <i class="la la-filter"></i>-->
                <!--    @lang('table.text_show_search_box')-->
                <!--    <i class="la la-angle-down"></i>-->
                <!--</button>-->
                <!--end::Dropdown-->
            </div>
        </div>
        <!--end::Header-->

        <!--begin::Body-->
        <div class="card-body">
            <!--begin::Search Form-->
            <div class="collapse col-xl-12 show" id="collapseExample">
                <div class="mb-7" id="filter_options">
                    <form id="submit_filter_w" method="post" action="{{Route('vendor.custom.vendorXLSFilter')}}">
                            @csrf
                    <div class="form-group row">
                        <div class="col-lg-3 mt-8">
                            
                            <select name="id_vendor" class="js-example-responsive" style="width: 100%" id="exampleSelect1">
                                <option value=""></option>
                                @foreach($vendors as $v)
                                 <option value="{{$v->id}}">{{$v->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-3">
                            <label for="code" class="form-control-label">@lang('delivery.code'):</label>
                            <input type="text" class="form-control" name="code" id="code" placeholder="@lang('delivery.code')"/>
                        </div>

                        <div class="col-lg-3">
                            <label for="s_email" class="form-control-label">@lang('table.search_email'):</label>
                            <input type="text" class="form-control" name="s_email" id="s_email" placeholder="@lang('table.search_email')"/>
                        </div>

                        <div class="col-lg-3 mt-7">
                            <button type="button" id="xls_report" style="width: 100%" class="btn btn-light-primary px-6 font-weight-bold search_btn">
                                <i class="la la-file-pdf"></i> {{trans('table.xls_report')}}
                            </a>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
            <!--end::Search Form-->
        </div>
        <!--end::Body-->
    </div>
    <!--end::Row-->
@endsection