@extends('panel._layout')
@push('style')
    <link href="{{asset('panel/assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
@endpush
@push('js')
    <script src="{{asset('panel/assets/plugins/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>
    <script>
        var data_table_url = '{!!route('deliveries.datatable')!!}';

        var Table = $('#data_table');
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
                "order": [[ 0, "desc" ]],
                ajax:{
                    "url":  data_table_url,
                    "type": 'GET',
                    "data": function(d) {
                        d.s_name = $('#filter_options #s_name').val(),
                        d.b_enabled = $('#filter_options #b_enabled').val()
                        d.s_mobile_number = $('#filter_options #s_mobile_number').val()
                        d.s_email = $('#filter_options #s_email').val()
                    }
                },
                columns: [
                    {className: 'text-center', data: 'DT_RowIndex', orderable: false, searchable: false},
                    {className: 'text-center', data: 'name', name: 'name', orderable: true, searchable: true},
                    {className: 'text-center', data: 'phone', name: 'phone', orderable: true, searchable: true},
                    {className: 'text-center', data: 'email', name: 'email', orderable: true, searchable: true},
                    {className: 'text-center', data: 'methodShipping', name: 'methodShipping', orderable: true, searchable: true},
                    {className: 'text-center', data: 'actions', name: 'actions', orderable: false, searchable: false}
                ]
            });

            $('.reset_form').click(function() {
                $('#filter_options #s_name').val('');
                $('#filter_options #s_mobile_number').val('');
                $('#filter_options #s_email').val('');
                $('#filter_options #b_enabled').val(0);
                Table.DataTable().ajax.reload();
            });


            $('.search_btn').click( function(ev) {
                Table.DataTable().ajax.reload();
            });
        });
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
        
        $('#xls_report').on('click', function() {
            console.log(123)
            $('#submit_filter_w').attr('action', '{{Route("deliveries.printXLSFilter")}}').submit();
        })
    </script>
@endpush
@section('subTitle')
    @lang('navbar.delivery')
@endsection
@section('pageTitle')
    <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
    <span class="text-muted font-weight-bold mr-4">@lang('navbar.driverOrder')</span>
    {{--<a href="#" class="btn btn-light-warning font-weight-bolder btn-sm">Add New</a>--}}
@endsection
@section('content')
    <!--begin::Row-->
    <div class="card card-custom">
        <!--begin::Header-->
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">
                    <i class="fas fa-dolly-flatbed icon-lg"></i>
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
            </div>
        </div>
        <!--end::Header-->

        <!--begin::Body-->
        <div class="card-body">
            <!--begin::Search Form-->
            <div class="collapse col-xl-12" id="collapseExample">
                <div class="mb-7" id="filter_options">
                    <form method="post" id="submit_filter_w" action="{{Route('deliveries.printPdfFilter')}}">
                            @csrf
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <label for="s_name" class="form-control-label">@lang('table.search_name')</label>
                            <input type="text" class="form-control" name="s_name" id="s_name" placeholder="@lang('table.search_name')"/>
                        </div>

                        <div class="col-lg-3">
                            <label for="s_mobile_number" class="form-control-label">@lang('table.search_mobile'):</label>
                            <input type="text" class="form-control" name="s_mobile_number" id="s_mobile_number" placeholder="@lang('table.search_mobile')"/>
                        </div>

                        <div class="col-lg-3">
                            <label for="s_email" class="form-control-label">@lang('table.search_email'):</label>
                            <input type="text" class="form-control" name="s_email" id="s_email" placeholder="@lang('table.search_email')"/>
                        </div>

                        <div class="col-lg-3">
                            <label for="b_enabled" class="form-control-label">@lang('table.status'):</label>
                            <select class="form-control col-sm-12" name="b_enabled" id="b_enabled">
                                <option value="0">--</option>
                                <option value="1">@lang('table.active')</option>
                                <option value="2">@lang('table.inactive')</option>
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
            <!--end::Search Form-->
            <!--begin: Datatable-->

            <table class="table table-striped- table-bordered table-hover table-checkable responsive no-wrap" id="data_table">
                <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">@lang('deliveryTable.name')</th>
                    <th class="text-center">@lang('deliveryTable.phone')</th>
                    <th class="text-center">@lang('deliveryTable.email')</th>
                    <th class="text-center">@lang('deliveryTable.method')</th>
                    <th class="text-center" style="width: 10%">@lang('deliveryTable.action')</th>
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
