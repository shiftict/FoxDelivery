@extends('panel._layout')
@section('subTitle')
    @lang('navbar.createStore')
@endsection
@push('js')
{{--    <script src="{{asset('panel/assets/js/pages/crud/forms/widgets/bootstrap-timepicker.js')}}"></script>--}}
    <script src="{{asset('panel/js/dropzone.js')}}"></script>
    <script src="{{asset('panel/assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('panel/assets/js/pages/crud/forms/widgets/form-repeater.js')}}"></script>
    <script src="https://unpkg.com/vue@next"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
        const AttributeBinding = {

            data() {
                return {
                    form: {
                        'vendore': {{ $id }},
                        'package': '',
                        'packageHours': {
                            'start_shift': '',
                            'end_shift': '',
                            'drivers': '',
                        },
                        'packageOrder': {
                            'pricing': '',
                            'stock': '',
                            'startL_order': '',
                            'endL_order': '',
                        },
                        'mainPackageHours': {
                            'start': '',
                            'end': '',
                            'pricing': '',
                        }
                    }, // form data
                    getDelivery: '{!! Route('get_drivers_vue') !!}', // route get delivery
                    postDelivery: '{!! Route('packages.store') !!}', // route post data
                    listDelivery: [], // list delivery
                    //selectedDriver: '',
                    selectedIndex: '',
                    fileds: [{'drivers': '',
                        'start_shift' : '',
                        'end_shift': '',
                        'option': []
                    }],
                    errors: [],
                }
            },
            created() {
                // this.getDriverFunction()
            },
            mounted() {
            },
            watch: {
            },
            methods: {
                getDriverFunction(index) {
                    // csrf token
                    let myToken =  '{!! csrf_token() !!}'
                    axios.defaults.headers.common = {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': myToken
                    };
                    // append data start time - end time
                    let formData = {
                        'end_shift' : this.fileds[index]['end_shift'],
                        'start_shift' : this.fileds[index]['start_shift'],
                    }
                    // request post with data start time - end time
                    axios.post(this.getDelivery, formData).then(response => {
                        // this.listDelivery = response.data.delivery
                        this.fileds[index].option = response.data.delivery
                    })
                },
                onChangeDrivers(event, index) {
                    this.form.packageHours = this.fileds
                    let idDelivery = event.target.value
                    this.form.packageHours.drivers = idDelivery
                },
                onChangeStatus(event, index) {
                    let statusId = event.target.value
                    this.form.status = statusId
                },
                onChangePackage(event) {
                    let package = event.target.value
                    this.form.package = package
                },
                addRows() {
                    this.fileds.push({'drivers': '',
                        'start_shift' : '',
                        'end_shift': ''
                    })
                },
                removeRows(index) {
                    this.fileds.splice(index, 1);
                },
                sendData() {
                    let myToken =  '{!! csrf_token() !!}'
                    axios.defaults.headers.common = {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': myToken
                    };
                    axios.post(this.postDelivery,  this.form).then(response => {
                        // this.listDelivery = response.data.delivery
                        console.log(response)
                        return 0;
                        toastr.options = {
                            "closeButton": false,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": true,
                            "positionClass": "toast-top-right",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        }
                        toastr.success('@lang("alert.successCreated")')
                    }).catch((e) => {

                        this.errors = e.response.data.errors

                        console.log(this.errors.mainPackageHours.pricing[0]);
                        toastr.options = {
                            "closeButton": false,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": true,
                            "positionClass": "toast-top-right",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        }
                        toastr.error('@lang("alert.errorMessage")')
                        {{--'{!! toastr()->info('Are you the 6 fingered man?') !!}'--}}
                        console.log(error.response.data.error.message); //Logs a string: Error: Request failed with status code 404
                    });
                },
                clearForm() {
                    this.fileds = [{'drivers': '',
                        'start_shift' : '',
                        'end_shift': '',
                        'option': []
                    }]
                    this.form.nameAr = ''
                    this.form.nameEn = ''
                    this.form.phone = ''
                    this.form.email = ''
                    this.form.address = ''
                    this.form.package = ''
                    this.form.status = ''
                    this.form.password = ''
                    this.form.packageHours.start_shift = ''
                    this.form.packageHours.start_shift.end_shift = ''
                    this.form.packageHours.start_shift.drivers = ''
                    this.form.packageOrder.pricing = ''
                    this.form.packageOrder.stock = ''
                    this.form.packageOrder.startL_order = ''
                    this.form.packageOrder.endL_order = ''
                    this.form.mainPackageHours.start = ''
                    this.form.mainPackageHours.end = ''
                    this.form.mainPackageHours.pricing = ''
                    this.selectedIndex = ''
                }
            },
        }

        Vue.createApp(AttributeBinding).mount('#app')
    </script>
@endpush
@section('pageTitle')
    <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
    <span class="text-muted font-weight-bold mr-4">@lang('store.newPackage')</span>
    {{--<a href="#" class="btn btn-light-warning font-weight-bolder btn-sm">Add New</a>--}}
@endsection
@section('content')
    <!--begin::Row-->
    <div class="row" id="app">
        <div class="col-md-12">
            <!--begin::Card-->
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header">
                    <h3 class="card-title">
                        <span class="fab fa-sketch icon-lg"></span>
                    </h3>

                </div>
                <!--begin::Form-->
                <form method="post" action="{{Route('vendor.store')}}">
                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label for="exampleSelect1">@lang('store.package')
                                    <span class="text-danger">*</span></label>
                                <select v-model="form.package" @change="onChangePackage($event)" name="package" class="form-control @error('package') is-invalid @enderror" id="package">
                                    <option value="">--</option>
                                    <option value="1">@lang('store.packagePerHours')</option>
                                    <option value="0">@lang('store.packagePerOrders')</option>
                                </select>
                                <form-error v-if="errors.package" :errors="errors">
                                    <div class="text-danger">  @{{ errors.package[0] }} </div>
                                </form-error>
                            </div>
                        </div>
                        <div v-if="form.package === '1'" id="hoursHr" disabled="none" class="separator separator-dashed my-10"></div>
                        <div v-if="form.package === '1'" id="hours" disabled="none" class="form-group row">
                            <div class="col-lg-12" id="kt_repeater_1">
                                <div v-for="(filed, key) in fileds" :key="key" class="form-group row">
                                    <div data-repeater-list="" class="col-lg-10">
                                        <div data-repeater-item="" class="form-group row align-items-center">

                                            <div class="col-md-3">
                                               <div class="input-group timepicker">
                                                   <input v-model="filed.start_shift" class="form-control" placeholder="@lang('store.endShift')" type="time" />
                                                   </div>
                                                   @if($errors->has('end_shift'))
                                                      <span class="text-danger">@lang('store.endHValidation')</span>
                                                   @endif
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group timepicker">
                                                    <input :disabled="filed.start_shift === ''" @change="getDriverFunction(key)" v-model="filed.end_shift" class="form-control" placeholder="@lang('store.endShift')" type="time" />
                                                </div>
                                                @if($errors->has('end_shift'))
                                                    <span class="text-danger">@lang('store.endHValidation')</span>
                                                @endif
                                            </div>
                                            <div class="col-md-3">
                                                <select :disabled="filed.end_shift === '' || filed.start_shift === ''" v-model="filed.drivers" name="drivers" @change="onChangeDrivers($event, key)" class="form-control @error('drivers') is-invalid @enderror">
                                                    <option value="">@lang('methodShippingTable.name')</option>
                                                    <option v-for="option in fileds[key].option" :value="option.id">
                                                        @{{ option.name }}
                                                    </option>
                                                </select>
                                                <form-error v-if="errors.pricing" :errors="errors">
                                                    <div class="text-danger">  @{{ errors.package[0] }} </div>
                                                </form-error>
                                            </div>
                                            <div class="col-md-2">
                                                <a @click="removeRows(key)" href="javascript:;" class="btn btn-sm font-weight-bolder btn-light-danger">
                                                    <i class="la la-trash-o"></i>@lang('form.delete')</a>
                                            </div>

                                        </div>

                                    </div>

                                </div>
                                <div class="col-md-2">
                                    <a href="javascript:;" @click="addRows" data-repeater-delete="" class="btn btn-sm font-weight-bolder btn-light-success">
                                        <i class="la la-plus-square"></i> @lang('form.add')</a>
                                </div>
                                <div class="form-group row">
                                    <div id="roro"></div>
                                    <label class="col-lg-2 col-form-label text-right"></label>
{{--                                    <div class="col-lg-4">--}}
{{--                                        <a id="add" href="javascript:;" data-repeater-create="" class="btn btn-sm font-weight-bolder btn-light-primary">--}}
{{--                                            <i class="la la-plus"></i>Add</a>--}}
{{--                                    </div>--}}
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="exampleSelect1">@lang('store.date')
                                    <span class="text-danger">*</span></label>
                                <div class="input-daterange input-group @error('start') is-invalid @enderror">
                                    <input v-model="form.mainPackageHours.start" autocomplete="off" min='{{now()->format('Y-m-d')}}' type="date" class="form-control"/>
                                    <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="la la-ellipsis-h"></i>
                                                                    </span>
                                    </div>
                                    <input v-model="form.mainPackageHours.end" autocomplete="off" min='{{now()->format('Y-m-d')}}' type="date" class="form-control" name="end" />
                                </div>
                                @if($errors->has('start'))
                                    <span class="text-danger">{{$errors->first('start')}} @lang('store.dateValidation')</span>
                                @else
                                <span class="form-text text-muted">@lang('store.dateDescription')</span>
                                @endif
                                @if($errors->has('end'))
                                    <span class="text-danger">{{$errors->first('end')}} @lang('store.dateValidation')</span>
                                @endif
                            </div>
                            <div class="col-lg-6">
                                <label>@lang('store.pricing')
                                    <span class="text-danger">*</span></label>
                                <div class="input-group ">
                                    <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        KWD
                                                    </span>
                                    </div>
                                    <input v-model="form.mainPackageHours.pricing" autocomplete="off" type="number" class="form-control @error('pricingh') is-invalid @enderror" name="pricingh" placeholder="@lang('store.pricingPlaceholder')" />
                                </div>
                            </div>
                        </div>
                        <div v-if="form.package === '0'" id="stockHr" disabled="none" class="separator separator-dashed my-10"></div>
                        <div v-if="form.package === '0'" id="stock" disabled="none" class="form-group row">
                            <div class="col-lg-6">
                                <label>@lang('store.stock')
                                    <span class="text-danger">*</span></label>
                                <div class="input-group ">
                                    <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <span class="svg-icon svg-icon-md"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Shopping/Box2.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                    <rect x="0" y="0" width="24" height="24"/>
                                                                    <path d="M4,9.67471899 L10.880262,13.6470401 C10.9543486,13.689814 11.0320333,13.7207107 11.1111111,13.740321 L11.1111111,21.4444444 L4.49070127,17.526473 C4.18655139,17.3464765 4,17.0193034 4,16.6658832 L4,9.67471899 Z M20,9.56911707 L20,16.6658832 C20,17.0193034 19.8134486,17.3464765 19.5092987,17.526473 L12.8888889,21.4444444 L12.8888889,13.6728275 C12.9050191,13.6647696 12.9210067,13.6561758 12.9368301,13.6470401 L20,9.56911707 Z" fill="#000000"/>
                                                                    <path d="M4.21611835,7.74669402 C4.30015839,7.64056877 4.40623188,7.55087574 4.5299008,7.48500698 L11.5299008,3.75665466 C11.8237589,3.60013944 12.1762411,3.60013944 12.4700992,3.75665466 L19.4700992,7.48500698 C19.5654307,7.53578262 19.6503066,7.60071528 19.7226939,7.67641889 L12.0479413,12.1074394 C11.9974761,12.1365754 11.9509488,12.1699127 11.9085461,12.2067543 C11.8661433,12.1699127 11.819616,12.1365754 11.7691509,12.1074394 L4.21611835,7.74669402 Z" fill="#000000" opacity="0.3"/>
                                                                </g>
                                                            </svg><!--end::Svg Icon-->
                                                        </span>
                                                    </span>
                                    </div>
                                    <input v-model="form.packageOrder.stock" autocomplete="off" type="number" class="form-control @error('stock') is-invalid @enderror" placeholder="@lang('store.stockPlaceholder')" />
                                </div>
                            </div>
                            <div class="col-lg-6 mb-9">
                                <label>@lang('store.pricing')
                                    <span class="text-danger">*</span></label>
                                <div class="input-group ">
                                    <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        KWD
                                                    </span>
                                    </div>
                                    <input v-model="form.packageOrder.pricing" autocomplete="off" type="number" class="form-control @error('pricing') is-invalid @enderror" placeholder="@lang('store.pricingPlaceholder')" />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="exampleSelect1">@lang('store.date')
                                    <span class="text-danger">*</span></label>
                                <div class="input-daterange input-group @error('startL_order') is-invalid @enderror" id="kt_datepicker_6">
                                    <input v-model="form.packageOrder.startL_order" autocomplete="off" type="date" min='{{now()->format('Y-m-d')}}' class="form-control" />
                                    <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="la la-ellipsis-h"></i>
                                                                    </span>
                                    </div>
                                    <input autocomplete="off" v-model="form.packageOrder.endL_order" type="date" min='{{now()->format('Y-m-d')}}' class="form-control" />
                                </div>
                                @if($errors->has('startL_order'))
                                    <span class="text-danger">{{$errors->first('startL_order')}} @lang('store.dateValidation')</span>
                                @else
                                    <span class="form-text text-muted">@lang('store.dateDescription')</span>
                                @endif
                                @if($errors->has('endL_order'))
                                    <span class="text-danger">{{$errors->first('endL_order')}} @lang('store.dateValidation')</span>
                                @endif
                            </div>
                            <div class="col-lg-6">


                                <div class="form-group row">
                                    <div class="col-9 col-form-label">
                                        <div class="checkbox-inline">

                                            <label class="checkbox checkbox-outline checkbox-success">
                                                <input type="checkbox" v-model="checked" name="Checkboxes15" checked="checked">
                                                <span></span>@{{ checked }}</label>
                                        </div>
                                        <span class="form-text text-muted">Some help text goes here</span>
                                    </div>
                                </div>
                            </div>
                        </div>





                    </div>
                    <div class="card-footer">
                        <button type="button" @click="sendData" class="btn btn-primary mr-2">@lang('form.create')</button>
                        <a href="{{Route('vendor.index')}}" class="btn btn-secondary">@lang('form.cancel')</a>
                    </div>
                </form>
                <!--end::Form-->
            </div>
            <!--end::Card-->
        </div>
    </div>
@endsection
