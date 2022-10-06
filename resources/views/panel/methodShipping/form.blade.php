@extends('admin.layout.main_v')
@section('services_active_class')
    @include('admin.layout.active_class')
@stop
@section('style')
    <link href="{{asset('assets/plugins/custom/gallery_assets/dropzone/dropzone.css')}}" rel="stylesheet" type="text/css" />
    <style>
        .ui-sortable-handle {
            position: relative;
        }
        .ui-sortable-handle .middle {
            width: 100%;
            height: 100%;
            transition: .5s ease;
            background-color: #fff;
            opacity: 0;
            position: absolute;
            transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            text-align: center;
        }
        .ui-sortable-handle .middle .text {
            position: absolute;
            font-weight: bold;
            top: 35%;
            left: 38%;
        }
        .ui-sortable-handle:hover .middle {
            opacity: 0.8;
        }

        .dropzone .dz-default.dz-message {
            background-image: unset !important;
        }
        .dropzone .dz-default.dz-message span {
            display: block !important;
        }
    </style>
@stop
@section('js')
    <script>
        let add_edit_url = '{!!route('admin.services.add_edit')!!}';
        $(document).ready(function() {
            validateForm();
            var service_logo = new KTImageInput('service_logo');

            service_logo.on('cancel', function(imageInput) {});

            service_logo.on('change', function(imageInput) {});
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#img_preview').attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
                $('#img_preview').removeClass('hide');
            }
        }

        function postData() {
            if (!$("#add_edit_form").valid()) {
                $("html, body").animate({
                    scrollTop: 0
                }, 600);
                return false;
            }

            $("#add_edit_form").submit();
        }

        function validateForm(){
            $("#add_edit_form").validate({
                rules: {
                    's_name[{{app()->getLocale()}}]': {
                        required: true,
                    },
                    's_description[{{app()->getLocale()}}]': {
                        required: true,
                    },
                },
                messages: {
                    's_name[{{app()->getLocale()}}]': {
                        required: "@lang('validation.required')",
                    },
                    's_description[{{app()->getLocale()}}]': {
                        required: "@lang('validation.required')",
                    }
                },
            });

            $(".fileupload").change(function(){
                readURL(this);
            });

            ClassicEditor.create( document.querySelector('#s_description' ), {
                ckfinder: {
                    uploadUrl: '{{route('admin.upload')}}',
                    '_token':'{{csrf_token()}}'
                },
                @if(app()->getLocale() == 'ar')
                language: {
                    ui: 'ar',
                    content: 'ar'
                }
                @endif
            }).then(editor => {window.editor = editor;}).catch(err => {console.error(err.stack);});

            $('.add_edit').click(function(e){
                e.preventDefault();
                postData();
            });
        }
    </script>
@endsection

@section('content')
    <div class="card card-custom">
        <!--begin::Header-->
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">
                    @if(isset($data['page_title']))
                        {{ $data['page_title'] }}
                    @endif
                </h3>
            </div>
        </div>
        <!--end::Header-->

        <!--begin::Body-->
        <div class="card-body">
            {!! FORM::open(['url'=>route('admin.services.add_edit'),'class'=>'form-horizontal','role'=>'form','id'=>'add_edit_form','files'=>'true','autocomplete'=>'off']) !!}
            <div class="row">
                <div class="form-group col-lg-6">
                    <label for="arabic_name" class="form-control-label">@lang('services.name'): <span class="required">*</span> @if(isset($service))<a href="javascript:;" onclick="show_localization_modal('{{$service->pk_i_id}}', 's_name','{{route('admin.services.show_localization_form')}}')">(@lang('general.add_locale'))</a>@endif</label>
                    <input type="text" placeholder="@lang('services.name')" name="s_name[{{app()->getLocale()}}]"  required id="arabic_name" class="form-control" value="@if(isset($service)){{$service->getTranslation('s_name', app()->getLocale())}}@endif">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-12">
                    <label for="s_description" class="form-control-label">@lang('services.description'):  @if(isset($service))<a href="javascript:;" onclick="show_localization_modal('{{$service->pk_i_id}}', 's_description','{{route('admin.services.show_localization_form')}}', true)">(@lang('general.add_locale'))</a>@endif</label>
                    <textarea name="s_description[{{app()->getLocale()}}]"  required id="s_description" class="form-control">@if(isset($service)){{$service->getTranslation('s_description', app()->getLocale())}}@endif</textarea>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6">
                    <label for="s_icon">@lang('services.icon') <span class="required">*</span></label>
                    <div></div>
                    <div class="image-input image-input-outline" id="service_logo" style="">
                        <div class="image-input-wrapper" style="background-image: url('@if(isset($service)){{url('uploads/services/'.$service->s_icon)}}@endif')"></div>

                        <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="@lang('services.change_image')">
                            <i class="fa fa-pen icon-sm text-muted"></i>
                            <input type="file"  name="s_icon"/>
                            <input type="hidden"  name="s_icon_remove"/>
                        </label>

                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="@lang('services.cancel_image')">
							<i class="ki ki-bold-close icon-xs text-muted"></i>
						</span>

                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="remove" data-toggle="tooltip" title="@lang('services.remove_image')">
							<i class="ki ki-bold-close icon-xs text-muted"></i>
						</span>
                    </div>
                </div>
            </div>
            <hr>
            @if(isset($service))
                <input type="hidden" id="service_id" name="service_id" value="{{$service->pk_i_id}}">
            @endif
            {!! FORM::close() !!}
            <div class="modal-footer">
                <button type="button" class="btn btn-primary add_edit">{{trans('general.text_ok')}}</button>
            </div>
        </div>
        <!--end::Body-->
    </div>
@stop
