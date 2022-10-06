@extends('panel._layout')
@push('style')
    <link href="{{asset('panel/assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
@endpush
@push('js')
    <script src="{{asset('panel/assets/plugins/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let add_edit_url = '{!!route('packages.datatable')!!}';
        let data_table_url = '{!!route('packages.datatable')!!}';

        let Modal = $('#add_edit');
        let Table = $('#data_table');

        $(document).ready(function() {
            Table.DataTable({
                processing: true,
                serverSide: true,
                "pageLength": 50,
                sDom: 'lrtip',
                "order": [[ 0, "ASC" ]],
                ajax:{
                    "url":  data_table_url,
                    "type": 'GET',
                    "data": function(d) {
                        d.s_name = $('#collapseExample #b_hourse').val(),
                            d.b_enabled = $('#collapseExample #b_enabled').val()
                    }
                },
                columns: [
                    {className: 'text-center', data: 'DT_RowIndex', name: 'id', orderable: true, searchable: false},
                    {className: 'text-center', data: 'number_of_orders', name: 'number_of_orders', orderable: true, searchable: true},
                    {className: 'text-center', data: 'expire_date', name: 's_name', orderable: false, searchable: false},
                    {className: 'text-center', data: 'price', name: 'price', orderable: true, searchable: true},
                    {className: 'text-center', data: 'type', name: 'type', orderable: true, searchable: true},
                    {className: 'text-center', data: 'b_enabled', name: 'status', orderable: true, searchable: true},
                    {className: 'text-center', data: 'actions', name: 'actions', orderable: false, searchable: false}
                ]
            });

            $('.reset_form').click(function() {
                $('#collapseExample #b_hourse').val('');
                $('#collapseExample #b_enabled').val('');
                Table.DataTable().ajax.reload();
            });

            $('.search_btn').click( function(ev) {
                Table.DataTable().ajax.reload();
            });
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

        function showModal(id,modal_id,url,token,validate){
            $("html, body").animate({
                scrollTop: 0
            }, 600);

            $.ajax({
                url : url,
                data : {'pk_i_id':id,'_token':token},
                type: "POST",
                beforeSend(){
                    block_page('{{trans('general.text_please_wait')}} ...');
                },
                success:function(data) {
                    if (data.success) {
                        $('#'+modal_id).html(data.page).modal('show',{dismissible: false,focus: false, backdrop: 'static', keyboard: false});
                        if (typeof validateForm === "function")
                            validateForm();
                    }
                    else
                        showAlertMessage('alert-danger', 'error !', 'An unknown error occured');
                    un_block_page();
                },
                error:function(data) {
                    un_block_page();
                } ,
                statusCode: {
                    500: function(data) {
                        un_block_page();
                    }
                }
            });
        }

        function postData(){
            if (!$("#add_edit_form").valid())
                return false;
            let data = new FormData($('#add_edit_form').get(0));

            var s_description = editor.getData();
            data.append('s_description[{{app()->getLocale()}}]', s_description);
            $.ajax({
                url : add_edit_url,
                data : data,
                type: "POST",
                processData: false,
                contentType: false,
                beforeSend(){
                    block_modal('add_edit','{{trans('general.text_please_wait')}} ...');
                },
                success:function(data) {
                    if(data.success){
                        Modal.modal('hide');
                        Table.DataTable().ajax.reload();
                        showAlertMessage('success','sss / ', data.message);
                    }
                    un_block_modal('modal');
                },
                error:function(data) {
                    console.log(data);
                    un_block_modal('modal');
                } ,
                statusCode: {
                    500: function(data) {
                        console.log(data);
                        un_block_modal('modal');
                    },
                    422: function(data) {
                        if(data.responseJSON.status.errors) {
                            const error_messages = Object.values(data.responseJSON.status.errors);
                            var message_to_show = '<ul style="padding-left:20px">';
                            for (const error_message of error_messages) {
                                message_to_show += '<li>'+error_message+'</li>';
                            }
                            message_to_show += '</ul>';
                            showAlertMessage('danger', 'sasd/ ', message_to_show, 10000);
                        }
                        un_block_modal('add_edit');
                    }
                }
            });
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

            $('.add_edit').click(function(e){
                e.preventDefault();
                postData();
            });
            ClassicEditor.create(document.querySelector('#add_edit_form #s_description'), {
                // toolbar: [ 'heading', '|', 'bold', 'italic', 'link' ]
            } )
                .then( editor => {
                    window.editor = editor;
                } )
                .catch( err => {
                    console.error( err.stack );
                } );
        }

        function DeleteConfirm(id, route){
            // if(confirm('هل أنت متأكد من ذلك؟'))
            //     return true;
            // else
            //     return false;
            return swal.fire({
                title: '{!!__('alert.confirme')!!}',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{!!__('alert.accept')!!}',
                cancelButtonText: '{!!__('alert.cancel')!!}'
            }).then(function(result){
                if(result.isConfirmed === true){
                    $.ajax({
                        url : route,
                        data : {'id':id,'_token':$('meta[name="token"]').attr('content')},
                        type: "DELETE",
                        beforeSend(){
                            block_page('{{trans('general.text_please_wait')}} ...');
                        },
                        success:function(data) {
                            console.log(data)
                            if (data.success == true) {
                                Table.DataTable().ajax.reload();
                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top-right',
                                    iconColor: 'greeen',
                                    customClass: {
                                        popup: 'colored-toast'
                                    },
                                    showConfirmButton: false,
                                    timer: 1500,
                                    timerProgressBar: true
                                })
                                Toast.fire({
                                    icon: 'success',
                                    title: '@lang('alert.deleteSuccess')'
                                })
                            }
                            else
                                showAlertMessage('alert-danger', 'error !', 'An unknown error occured');
                        },
                        error:function(data) {
                        } ,
                        statusCode: {
                            500: function(data) {
                                // un_block_page();
                            }
                        }
                    });
                    un_block_page();
                    return true;
                }else{
                    return false;
                }
            });
        }
    </script>
@endpush
@section('subTitle')
    @lang('navbar.city')
@endsection
@section('pageTitle')
    <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
    <span class="text-muted font-weight-bold mr-4">@lang('package.management_package')</span>
    {{--<a href="#" class="btn btn-light-warning font-weight-bolder btn-sm">Add New</a>--}}
@endsection
@section('content')
    <!--begin::Row-->
    <div class="card card-custom">
        <!--begin::Header-->
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">
                    <span class="fas fa-map icon-lg"></span>
                </h3>
            </div>
            <div class="card-toolbar">
                <!--begin::Dropdown-->
                <button style="margin: 0 10px 0 10px" id="btn_show_search_box" onclick="check_collapse()" class="btn btn-light-primary font-weight-bolder" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                    <i class="la la-filter"></i>
                    @lang('table.text_show_search_box')
                    <i class="la la-angle-down"></i>
                </button>
                <!--end::Dropdown-->
                @permission('users_create')
                <a href="{{route('packages.newPackage.new.subscription')}}" class="btn btn-primary font-weight-bolder">
                    <i class="la la-plus-circle"></i>
                    @lang('table.create')
                </a>
                @endpermission
            </div>
        </div>
        <!--end::Header-->

        <!--begin::Body-->
        <div class="card-body">
            <!--begin::Search Form-->
            <div class="collapse col-xl-12" id="collapseExample">
                <div class="mb-7" id="filter_options">
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <label for="b_hourse" class="form-control-label">@lang('table.status'):</label>
                            <select class="form-control col-sm-12" name="b_hourse" id="b_hourse">
                                <option value="">--</option>
                                <option value="0">@lang('store.packagePerOrders')</option>
                                <option value="1">@lang('store.packagePerHours')</option>
                            </select>
                        </div>

                        <div class="col-lg-3">
                            <label for="b_enabled" class="form-control-label">@lang('table.status'):</label>
                            <select class="form-control col-sm-12" name="b_enabled" id="b_enabled">
                                <option value="">--</option>
                                <option value="1">@lang('table.active')</option>
                                <option value="0">@lang('table.inactive')</option>
                            </select>
                        </div>
                    </div>

                    <div class="row align-items-center">
                        <div class="col-lg-3 mt-5">
                            <a href="javascript:;" style="width: 100%" class="btn btn-light-primary px-6 font-weight-bold search_btn">
                                <i class="la la-search"></i> @lang('table.text_search')
                            </a>
                        </div>

                        <div class="col-lg-3 mt-5">
                            <a href="javascript:;" style="width: 100%" class="btn btn-light-dark px-6 font-weight-bold reset_form">
                                <i class="la la-search"></i> @lang('table.text_cancel_search')
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Search Form-->
            <!--begin: Datatable-->

            <table class="table table-striped- table-bordered table-hover table-checkable responsive no-wrap" id="data_table">
                <thead>
                <tr>
                    <th class="text-center" style="width: 10%">#</th>
                    <th class="text-center">@lang('package.number_of_orders')</th>
                    <th class="text-center">@lang('package.expire')</th>
                    <th class="text-center">@lang('package.price')</th>
                    <th class="text-center">@lang('package.package')</th>
                    <th class="text-center" style="width: 10%">@lang('table.status')</th>
                    <th class="text-center" style="width: 10%">@lang('cityTable.text_options')</th>
                </tr>
                </thead>
            </table>
            <!--end: Datatable-->
        </div>
        <!--end::Body-->
    </div>
    <!--end::Row-->
@endsection
@push('js')
    <script>
        function check_collapse() {
            let in_show_btn = '<i class="la la-filter"></i> ' + ' ' + ' @lang('table.text_show_search_box') ' + ' ' + ' <i class="la la-angle-down"></i>'
            let in_hide_btn = '<i class="la la-filter"></i>' + ' ' + ' @lang('table.text_hide_search_box') ' + ' ' + ' <i class="la la-angle-up"></i>'

            if ($('#collapseExample').hasClass('show')) {
                $('#btn_show_search_box').html(in_show_btn)
            } else {
                $('#btn_show_search_box').html(in_hide_btn)
            }
        }
    </script>
@endpush
