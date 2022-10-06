@extends('panel._layout')
@push('style')
    <link href="{{asset('panel/assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
@endpush
@push('js')
    <script src="{{asset('panel/assets/plugins/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let add_edit_url = '{!!route('orders.datatable')!!}';
        let data_table_url = '{!!route('orders.datatable')!!}';

        let Modal = $('#add_edit');
        let Table = $('#data_table');

        $(document).ready(function() {
            Table.DataTable({
                @if(app()->getLocale() == 'ar')
                        language: {
                            "sEmptyTable":     "ليست هناك بيانات متاحة في الجدول",
                            "sLoadingRecords": "جارٍ التحميل...",
                            "sProcessing":   "جارٍ التحميل...",
                            "sLengthMenu":   "أظهر _MENU_ مدخلات",
                            "sZeroRecords":  "لم يعثر على أية سجلات",
                            "sInfo":         "إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخل",
                            "sInfoEmpty":    "يعرض 0 إلى 0 من أصل 0 سجل",
                            "sInfoFiltered": "(منتقاة من مجموع _MAX_ مُدخل)",
                            "sInfoPostFix":  "",
                            "sSearch":       "ابحث:",
                            "sUrl":          "",
                            "oPaginate": {
                                "sFirst":    "الأول",
                                "sPrevious": "السابق",
                                "sNext":     "التالي",
                                "sLast":     "الأخير"
                            },
                            "oAria": {
                                "sSortAscending":  ": تفعيل لترتيب العمود تصاعدياً",
                                "sSortDescending": ": تفعيل لترتيب العمود تنازلياً"
                            }
                        },
                        @else
                            language:{
                            "emptyTable": "No data available in table",
                            "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                            "infoEmpty": "Showing 0 to 0 of 0 entries",
                            "infoFiltered": "(filtered from _MAX_ total entries)",
                            "infoThousands": ",",
                            "lengthMenu": "Show _MENU_ entries",
                            "loadingRecords": "Loading...",
                            "processing": "Processing...",
                            "search": "Search:",
                            "zeroRecords": "No matching records found",
                            "thousands": ",",
                            "paginate": {
                                "first": "First",
                                "last": "Last",
                                "next": "Next",
                                "previous": "Previous"
                            },
                            "aria": {
                                "sortAscending": ": activate to sort column ascending",
                                "sortDescending": ": activate to sort column descending"
                            },
                            "autoFill": {
                                "cancel": "Cancel",
                                "fill": "Fill all cells with <i>%d<\/i>",
                                "fillHorizontal": "Fill cells horizontally",
                                "fillVertical": "Fill cells vertically"
                            },
                            "buttons": {
                                "collection": "Collection <span class='ui-button-icon-primary ui-icon ui-icon-triangle-1-s'\/>",
                                "colvis": "Column Visibility",
                                "colvisRestore": "Restore visibility",
                                "copy": "Copy",
                                "copyKeys": "Press ctrl or u2318 + C to copy the table data to your system clipboard.<br><br>To cancel, click this message or press escape.",
                                "copySuccess": {
                                    "1": "Copied 1 row to clipboard",
                                    "_": "Copied %d rows to clipboard"
                                },
                                "copyTitle": "Copy to Clipboard",
                                "csv": "CSV",
                                "excel": "Excel",
                                "pageLength": {
                                    "-1": "Show all rows",
                                    "_": "Show %d rows"
                                },
                                "pdf": "PDF",
                                "print": "Print",
                                "updateState": "Update",
                                "stateRestore": "State %d",
                                "savedStates": "Saved States",
                                "renameState": "Rename",
                                "removeState": "Remove",
                                "removeAllStates": "Remove All States",
                                "createState": "Create State"
                            },
                            "searchBuilder": {
                                "add": "Add Condition",
                                "button": {
                                    "0": "Search Builder",
                                    "_": "Search Builder (%d)"
                                },
                                "clearAll": "Clear All",
                                "condition": "Condition",
                                "conditions": {
                                    "date": {
                                        "after": "After",
                                        "before": "Before",
                                        "between": "Between",
                                        "empty": "Empty",
                                        "equals": "Equals",
                                        "not": "Not",
                                        "notBetween": "Not Between",
                                        "notEmpty": "Not Empty"
                                    },
                                    "number": {
                                        "between": "Between",
                                        "empty": "Empty",
                                        "equals": "Equals",
                                        "gt": "Greater Than",
                                        "gte": "Greater Than Equal To",
                                        "lt": "Less Than",
                                        "lte": "Less Than Equal To",
                                        "not": "Not",
                                        "notBetween": "Not Between",
                                        "notEmpty": "Not Empty"
                                    },
                                    "string": {
                                        "contains": "Contains",
                                        "empty": "Empty",
                                        "endsWith": "Ends With",
                                        "equals": "Equals",
                                        "not": "Not",
                                        "notEmpty": "Not Empty",
                                        "startsWith": "Starts With",
                                        "notContains": "Does Not Contain",
                                        "notStarts": "Does Not Start With",
                                        "notEnds": "Does Not End With"
                                    },
                                    "array": {
                                        "without": "Without",
                                        "notEmpty": "Not Empty",
                                        "not": "Not",
                                        "contains": "Contains",
                                        "empty": "Empty",
                                        "equals": "Equals"
                                    }
                                },
                                "data": "Data",
                                "deleteTitle": "Delete filtering rule",
                                "leftTitle": "Outdent Criteria",
                                "logicAnd": "And",
                                "logicOr": "Or",
                                "rightTitle": "Indent Criteria",
                                "title": {
                                    "0": "Search Builder",
                                    "_": "Search Builder (%d)"
                                },
                                "value": "Value"
                            },
                            "searchPanes": {
                                "clearMessage": "Clear All",
                                "collapse": {
                                    "0": "SearchPanes",
                                    "_": "SearchPanes (%d)"
                                },
                                "count": "{total}",
                                "countFiltered": "{shown} ({total})",
                                "emptyPanes": "No SearchPanes",
                                "loadMessage": "Loading SearchPanes",
                                "title": "Filters Active - %d",
                                "showMessage": "Show All",
                                "collapseMessage": "Collapse All"
                            },
                            "select": {
                                "cells": {
                                    "1": "1 cell selected",
                                    "_": "%d cells selected"
                                },
                                "columns": {
                                    "1": "1 column selected",
                                    "_": "%d columns selected"
                                }
                            },
                            "datetime": {
                                "previous": "Previous",
                                "next": "Next",
                                "hours": "Hour",
                                "minutes": "Minute",
                                "seconds": "Second",
                                "unknown": "-",
                                "amPm": [
                                    "am",
                                    "pm"
                                ],
                                "weekdays": [
                                    "Sun",
                                    "Mon",
                                    "Tue",
                                    "Wed",
                                    "Thu",
                                    "Fri",
                                    "Sat"
                                ],
                                "months": [
                                    "January",
                                    "February",
                                    "March",
                                    "April",
                                    "May",
                                    "June",
                                    "July",
                                    "August",
                                    "September",
                                    "October",
                                    "November",
                                    "December"
                                ]
                            },
                            "editor": {
                                "close": "Close",
                                "create": {
                                    "button": "New",
                                    "title": "Create new entry",
                                    "submit": "Create"
                                },
                                "edit": {
                                    "button": "Edit",
                                    "title": "Edit Entry",
                                    "submit": "Update"
                                },
                                "remove": {
                                    "button": "Delete",
                                    "title": "Delete",
                                    "submit": "Delete",
                                    "confirm": {
                                        "_": "Are you sure you wish to delete %d rows?",
                                        "1": "Are you sure you wish to delete 1 row?"
                                    }
                                },
                                "error": {
                                    "system": "A system error has occurred (<a target=\"\\\" rel=\"nofollow\" href=\"\\\">More information<\/a>)."
                                },
                                "multi": {
                                    "title": "Multiple Values",
                                    "info": "The selected items contain different values for this input. To edit and set all items for this input to the same value, click or tap here, otherwise they will retain their individual values.",
                                    "restore": "Undo Changes",
                                    "noMulti": "This input can be edited individually, but not part of a group. "
                                }
                            },
                            "stateRestore": {
                                "renameTitle": "Rename State",
                                "renameLabel": "New Name for %s:",
                                "renameButton": "Rename",
                                "removeTitle": "Remove State",
                                "removeSubmit": "Remove",
                                "removeJoiner": " and ",
                                "removeError": "Failed to remove state.",
                                "removeConfirm": "Are you sure you want to remove %s?",
                                "emptyStates": "No saved states",
                                "emptyError": "Name cannot be empty.",
                                "duplicateError": "A state with this name already exists.",
                                "creationModal": {
                                    "toggleLabel": "Includes:",
                                    "title": "Create New State",
                                    "select": "Select",
                                    "searchBuilder": "SearchBuilder",
                                    "search": "Search",
                                    "scroller": "Scroll Position",
                                    "paging": "Paging",
                                    "order": "Sorting",
                                    "name": "Name:",
                                    "columns": {
                                        "visible": "Column Visibility",
                                        "search": "Column Search"
                                    },
                                    "button": "Create"
                                }
                            }
                        },
                        @endif
                processing: true,
                serverSide: true,
                "pageLength": 50,
                sDom: 'lrtip',
                "order": [[ 0, "ASC" ]],
                ajax:{
                    "url":  data_table_url,
                    "type": 'GET',
                    "data": function(d) {
                        d.s_name = $('#collapseExample #s_name').val()
                        d.b_enabled = $('#collapseExample #b_enabled').val()
                        d.date_from = $('#collapseExample #date_from').val()
                        d.date_to = $('#collapseExample #date_to').val()
                        d.driver = $('#collapseExample #driver').val()
                        d.vendor = $('#collapseExample #vendor').val()
                    }
                },
                columns: [
                    {className: 'text-center', data: 'DT_RowIndex', orderable: false, searchable: false},
                    {className: 'text-center', data: 'name', name: 'name', orderable: false, searchable: true},
                    {className: 'text-center', data: 'from', name: 'from', orderable: false, searchable: true},
                    {className: 'text-center', data: 'to', name: 'to', orderable: false, searchable: true},
                    {className: 'text-center', data: 'phone', name: 'phone', orderable: false, searchable: true},
                    {className: 'text-center', data: 'vendor', name: 'vendor', orderable: false, searchable: true},
                    {className: 'text-center', data: 'actions', name: 'actions', orderable: false, searchable: false}
                ]
            });

            $('.reset_form').click(function() {
                $('#collapseExample #s_name').val('');
                $('#collapseExample #b_enabled').val('');
                $('#collapseExample #date_from').val('')
                $('#collapseExample #date_to').val('')
                $('#collapseExample #driver').val('')
                $('#collapseExample #vendor').val('')
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
        function check_collapse() {
            let in_show_btn = '<i class="la la-filter"></i> ' + ' ' + ' @lang('table.text_show_search_box') ' + ' ' + ' <i class="la la-angle-down"></i>'
            let in_hide_btn = '<i class="la la-filter"></i>' + ' ' + ' @lang('table.text_hide_search_box') ' + ' ' + ' <i class="la la-angle-up"></i>'

            if ($('#collapseExample').hasClass('show')) {
                $('#btn_show_search_box').html(in_show_btn)
            } else {
                $('#btn_show_search_box').html(in_hide_btn)
            }
        }
        
        $('#xls_report').on('click', function() {
            $('#submit_filter_w').attr('action', '{{Route("order.orderXLSFilter")}}').submit();
        })
    </script>
@endpush
@section('subTitle')
    @lang('navbar.order')
@endsection
@section('pageTitle')
    <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
    <span class="text-muted font-weight-bold mr-4">@lang('navbar.reportOrder')</span>
    {{--<a href="#" class="btn btn-light-warning font-weight-bolder btn-sm">Add New</a>--}}
@endsection
@section('content')
    <!--begin::Row-->
    <div class="card card-custom">
        <!--begin::Header-->
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">
                    <span class="fas fa-shopping-bag icon-lg"></span>
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
                <!--begin::Button-->
                {{--				<a href="javascript:;" onclick="showModal(null,'add_edit','{{ route('admin.services.form')}}','{{csrf_token()}}')" class="btn btn-primary font-weight-bolder">--}}

                <!--end::Button-->
            </div>
        </div>
        <!--end::Header-->

        <!--begin::Body-->
        <div class="card-body">
            <!--begin::Search Form-->
            <div class="collapse col-xl-12" id="collapseExample">
                <div class="mb-7" id="filter_options">
                    
                        <form method="post" id="submit_filter_w" action="{{Route('order.printPdfFilter')}}">
                            @csrf
                    <div class="row align-items-center">
                        <div class="col-lg-3">
                            <label for="s_name" class="form-control-label">@lang('table.search_name')</label>
                            <input type="text" class="form-control" name="s_name" id="s_name" placeholder="@lang('table.search_name')"/>
                        </div>

                        <div class="col-lg-3">
                            <label for="s_name" class="form-control-label">@lang('table.from')</label>
                            <input type="date" class="form-control" name="date_from" id="date_from" placeholder="@lang('table.search_name')"/>
                        </div>

                        <div class="col-lg-3">
                            <label for="s_name" class="form-control-label">@lang('table.to')</label>
                            <input type="date" class="form-control" name="date_to" id="date_to" placeholder="@lang('table.search_name')"/>
                        </div>

                        <div class="col-lg-3">
                            <label for="b_enabled" class="form-control-label">@lang('table.status'):</label>
                            <select class="form-control col-sm-12" name="b_enabled" id="b_enabled">
                                <option value="">--</option>
                                @foreach (App\Models\StatusSystem::where('status', '1')->get() as $status)
                                    <option class="status_drive_option" value="{{ $status->id }}">{{ $status->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @role('superadministrator')
                        <div class="col-lg-3">
                            <label for="b_enabled" class="form-control-label">@lang('orderTable.vendor'):</label>
                            <select class="form-control col-sm-12" name="vendor" id="vendor">
                                <option value="">--</option>
                                @foreach(App\Models\Vendor::where('status', '1')->get() as $vendor)
                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label for="b_enabled" class="form-control-label">@lang('orderTable.driver'):</label>
                            <select class="form-control col-sm-12" name="driver" id="driver">
                                <option value="">--</option>
                                @foreach(App\Models\Delivery::where('status', '1')->get() as $driver)
                                <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endrole
                        
                    </div>
                    <div class="row align-items-center">
                        <div class="col-lg-3 mt-5">
                            <a href="javascript:;" style="width: 100%" class="btn btn-light-primary px-6 font-weight-bold search_btn">
                                <i class="la la-search"></i> @lang('table.text_search')
                            </a>
                        </div>

                        <div class="col-lg-3 mt-5">
                            <a href="javascript:;" style="width: 100%" class="btn btn-light-dark px-6 font-weight-bold reset_form">
                                <i class="la la-search"></i> {{trans('table.text_cancel_search')}}
                            </a>
                        </div>
                        
                        <div class="col-lg-3 mt-5">
                            <button type="submit" href="javascript:;" style="width: 100%" class="btn btn-light-success px-6 font-weight-bold">
                                <i class="la la-file-pdf"></i> {{trans('table.pdf_report')}}
                            </a>
                        </div>
                        
                        <div class="col-lg-3 mt-5">
                            <button type="button" id="xls_report" href="javascript:;" style="width: 100%" class="btn btn-light-info px-6 font-weight-bold">
                                <i class="la la-file-pdf"></i> {{trans('table.xls_report')}}
                            </a>
                        </div>
                    </div>
                    </form>
                </div>
            </div>

            <table class="table table-striped- table-bordered table-hover table-checkable responsive no-wrap" id="data_table">
                <thead>
                <tr>
                    <th class="text-center" style="width: 10%">#</th>
                    <th class="text-center">@lang('orderTable.name')</th>
                    <th class="text-center">@lang('orderTable.from')</th>
                    <th class="text-center">@lang('orderTable.to')</th>
                    <th class="text-center">@lang('orderTable.phone')</th>
                    <th class="text-center">@lang('orderTable.vendor')</th>
                    <th class="text-center" style="width: 10%">{{trans('areaTable.text_options')}}</th>
                </tr>
                </thead>
            </table>
            <!--end: Datatable-->
        </div>
        <!--end::Body-->
    </div>
    <!--end::Row-->
@endsection
