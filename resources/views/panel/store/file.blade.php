@extends('panel._layout')
@section('subTitle')
    @lang('store.file')
@endsection
@push('js')
    <script>
        $( document ).ready(function() {
            $('#vendorAttacheds').dropzone({
                url: "/dashboard/vendor/attacheds", // Set the url for your upload script location
                paramName: "file", // The name that will be used to transfer the file
                maxFiles: 10,
                maxFilesize: 10, // MB
                addRemoveLinks: true,
                acceptedFiles: "image/*,application/pdf", /*is this correct?*/
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="token"]').attr('content')
                },
                accept: function(file, done) {
                    if (file.name == "justinbieber.jpg") {
                        done("Naha, you don't.");
                    } else {
                        done();
                    }
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
    <span class="text-muted font-weight-bold mr-4">@lang('store.file')</span>
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
                        <span class="fas flaticon2-image-file icon-lg"></span>
                    </h3>


                </div>
                <div class="row mb-7">
                    <!--begin::Form-->
                    @foreach($attachment as $at)
                        <div class="col-lg-4">
                            <!--begin::Card-->
                            <div class="card card-custom">
                                <div class="card-header ribbon ribbon-top ribbon-ver">
                                    <div class="ribbon-target bg-success" style="top: -2px; right: 20px;">
                                        <a target="_blank" href="{{url(asset($at->path))}}"><i class="fas fa-eye text-white"></i></a> &nbsp &nbsp &nbsp
                                        <a href="{{route('delete.attachment', $at->id)}}"><i class="fas fa-trash text-white"></i></a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if($at->ext == 'jpg' || $at->ext == 'JPG' || $at->ext == 'png' || $at->ext == 'PNG' || $at->ext == 'jpeg' || $at->ext == 'JPEG' || $at->ext == 'gif' || $at->ext == 'GIF')
                                        <img style="width: 100%;height: auto;" src="{{asset($at->path)}}">
                                    @endif
                                    @if($at->ext == 'pdf' || $at->ext == 'PDF')
                                        <img  style="width: 100%;height: auto;" src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/87/PDF_file_icon.svg/267px-PDF_file_icon.svg.png">
                                    @endif

                                </div>

                            </div>
                            <!--end::Card-->
                        </div>
                    @endforeach
                </div>
                <!--end::Form-->
            </div>
            <!--end::Card-->
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <label>@lang('store.attachments')</label>
            <div class="dropzone dropzone-default dropzone-primary" id="vendorAttacheds">
                <div class="dropzone-msg dz-message needsclick">
                    <h3 class="dropzone-msg-title">@lang('store.descriptionAttachments')</h3>
                    <span class="dropzone-msg-desc">@lang('store.limitAttachments')</span>
                </div>
            </div>
            <form id="kt_form_1" method="post" action="{{Route('vendor.edit.file')}}">
                @csrf
                <input type="hidden" name="store" value="{{$id}}">
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary mr-2">@lang('form.update')</button>
                </div>
            </form>
        </div>
    </div>
@endsection
