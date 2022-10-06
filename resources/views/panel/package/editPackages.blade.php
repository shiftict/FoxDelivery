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
                        'type_package': '',
                        'packageHours': {
                            'vehicle': '',
                            'hours' : '',
                            'drivers': '',
                        },
                        'packageOrder': {
                            'pricing': '',
                            'stock': '',
                            'start': '',
                            'end': '',
                        },
                        'mainPackageHours': {
                            'start': '',
                            'end': '',
                            'pricing': '',
                            'drivers': '',
                        },
                        'timer_driver': '',
                    }, // form data
                    getDelivery: '{!! Route('get_drivers_vue') !!}', // route get delivery
                    postDelivery: '{!! Route('packages.edit.update') !!}', // route post data
                    postPackages: '{!! Route('packages.edit.show', $id) !!}', // route post data
                    listDelivery: [], // list delivery
                    //selectedDriver: '',
                    selectedIndex: '',
                    fileds: [{'vehicle': '',
                        'hours' : '',
                    }],
                    display_btn: false,
                    errors: [],
                    show_tow_form: false,
                    show_hours_form: false,
                    show_order_form: false,
                    option_vehicle: [],
                    showOrderMessage: '@lang("package.package_for_order")',
                    showHoursMessage: '@lang("package.package_for_hours")',
                    // validation
                    validPackageHoursPricing: '',
                    validPackageHoursStart: '',
                    validPackageHoursEnd: '',
                    validPackageHoursDrivers: '',
                    validPackageOrderPricing: '',
                    validPackageOrderStart: '',
                    validPackageOrderEnd: '',
                    validPackageOrderStock: '',
                    typeOrderDelete: false,
                    typeHoursDelete: false, // 1 - peer hour | 0 - peer order
                    showButtonHourse: false,
                    showButtonOrder: false,
                }
            },
            created() {
                // this.getDriverFunction()
                this.getDriverFunction()
                this.getPackages()
            },
            mounted() {
            },
            computed: {
                drivers() {
                    return this.form.mainPackageHours.drivers;
                }
            },
            watch: {
                // drivers:{
                //     handler: function(val) {
                //         this.fileds = []
                //         if(val.length == 0) {
                //             this.fileds = []
                //             return 0;
                //         }
                //         for(let i = 1; this.drivers >= i; i++) {
                //             this.fileds.push({'vehicle': '',
                //                 'hours' : '',
                //                 'option': []
                //             })
                //         }
                //         this.form.lat = $("input[name=lat_id]").val()
                //         this.form.long = $("input[name=long_id]").val()
                //     }
                // },
            },
            methods: {
                getPackages() {
                    let self = this;
                    let myToken =  '{!! csrf_token() !!}'
                    axios.get(this.postPackages)
                        .then(function (response) {
                            // handle success
                            // this.mainPackagesData = response.data
                            if(response.data.data.length == 2) {
                                self.show_tow_form = true
                                self.form.type_package = 2
                                response.data.data.forEach((value, index) => {
                                    if(value.stock === null) {
                                        self.form.mainPackageHours.start = value.start
                                        self.form.mainPackageHours.end = value.end
                                        self.form.mainPackageHours.pricing = value.pricing
                                        self.form.mainPackageHours.drivers = value.driver_number
                                        self.fileds = value.driver
                                    } else {
                                        self.form.packageOrder.start = value.start
                                        self.form.packageOrder.end = value.end
                                        self.form.packageOrder.pricing = value.pricing
                                        self.form.packageOrder.stock = value.stock
                                    }
                                });
                            } else if(response.data.data[0].stock === null) {
                                self.show_hours_form = true
                                self.form.type_package = 1
                                self.typeOrderDelete = true
                                response.data.data.forEach((value, index) => {
                                    self.form.mainPackageHours.start = value.start
                                    self.form.mainPackageHours.end = value.end
                                    self.form.mainPackageHours.pricing = value.pricing
                                    self.form.mainPackageHours.drivers = value.driver_number
                                    self.fileds = value.driver
                                });
                                self.showButtonHourse = true
                            } else {
                                self.show_order_form = true
                                self.form.type_package = 0
                                self.typeHoursDelete = true
                                response.data.data.forEach((value, index) => {
                                    self.form.packageOrder.start = value.start
                                    self.form.packageOrder.end = value.end
                                    self.form.packageOrder.pricing = value.pricing
                                    self.form.packageOrder.stock = value.stock
                                });
                                self.showButtonOrder = true
                            }
                        })
                        .catch(function (error) {
                            // handle error
                            console.log(error);
                        })
                },
                getDriverFunction() {
                    // csrf token
                    let myToken =  '{!! csrf_token() !!}'
                    axios.defaults.headers.common = {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': myToken
                    };
                    // append data start time - end time
                    // let formData = {
                    //     'end_shift' : this.fileds[index]['end_shift'],
                    //     'start_shift' : this.fileds[index]['start_shift'],
                    // }
                    // request post with data start time - end time
                    axios.post(this.getDelivery).then(response => {
                        // this.listDelivery = response.data.delivery
                        this.option_vehicle = response.data.delivery
                    })
                },
                onChangeDrivers(event, index) {
                    // this.form.packageHours = this.fileds
                    let idDelivery = event.target.value
                    this.fileds['index'].drivers = idDelivery
                },
                clearValidation() {
                    this.validPackageHoursPricing = ''
                    this.validPackageHoursStart = ''
                    this.validPackageHoursEnd = ''
                    this.validPackageOrderPricing = ''
                    this.validPackageOrderStart = ''
                    this.validPackageOrderEnd = ''
                    this.validPackageOrderStock = ''
                },
                sendData() {
                    this.clearValidation()
                    // this.display_btn = true
                    let self = this
                    this.form.timer_driver = this.fileds
                    let myToken =  '{!! csrf_token() !!}'
                    axios.defaults.headers.common = {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': myToken
                    };
                    axios.post(this.postDelivery,  this.form).then(response => {
                        // this.listDelivery = response.data.delivery
                        if(response.message) {
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
                        } else {
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
                        }
                        window.location.href = '{!! Route('vendor.index') !!}';
                    }).catch((error) => {
                        this.display_btn = false
                        // let arrayV = []
                        self.errors.push(error.response.data.errors)
                        //Logs a string: Error: Request failed with status code 404
                        // peer hours
                        if(error.response.data.errors['mainPackageHours.pricing'] != undefined){
                            this.validPackageHoursPricing = error.response.data.errors['mainPackageHours.pricing'][0];
                        }
                        if(error.response.data.errors['mainPackageHours.start'] != undefined){
                            this.validPackageHoursStart = error.response.data.errors['mainPackageHours.start'][0];
                        }
                        if(error.response.data.errors['mainPackageHours.end'] != undefined){
                            this.validPackageHoursEnd = error.response.data.errors['mainPackageHours.end'][0];
                        }
                        if(error.response.data.errors['mainPackageHours.drivers'] != undefined){
                            this.validPackageHoursDrivers = error.response.data.errors['mainPackageHours.drivers'][0];
                        }
                        // peer order
                        if(error.response.data.errors['packageOrder.pricing'] != undefined){
                            this.validPackageOrderPricing = error.response.data.errors['packageOrder.pricing'][0];
                        }
                        if(error.response.data.errors['packageOrder.start'] != undefined){
                            this.validPackageOrderStart = error.response.data.errors['packageOrder.start'][0];
                        }
                        if(error.response.data.errors['packageOrder.end'] != undefined){
                            this.validPackageOrderEnd = error.response.data.errors['packageOrder.end'][0];
                        }
                        if(error.response.data.errors['packageOrder.stock'] != undefined){
                            this.validPackageOrderStock = error.response.data.errors['packageOrder.stock'][0];
                        }
                        // console.log(this.errors.mainPackageHours.pricing[0]);
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
                    this.fileds= [{'vehicle': '',
                        'hours' : '',
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
                },
                changeDriverNumber() {
                    this.fileds = []
                            if(this.form.mainPackageHours.drivers.length == 0 || this.form.mainPackageHours.drivers == null) {
                                this.fileds = []
                                return 0;
                            }
                            if(parseInt(this.form.mainPackageHours.drivers) > 10){
                              this.fileds = []
                                return 0;  
                            }
                            for(let i = 1; this.drivers >= i; i++) {
                                this.fileds.push({'vehicle': '',
                                    'hours' : '',
                                })
                            }
                },
                changeFormOrder() {
                    if(this.show_order_form == false) {
                        this.showOrderMessage = '@lang("package.package_for_order")'
                        this.show_order_form = true
                        // this.showButtonHourse = false
                        this.form.type_package = 2
                    } else {
                        this.showOrderMessage = '@lang("package.package_for_order")'
                        // this.show_order_form = false
                        // this.form.type_package = 1
                    }
                },
                changeFormHours() {
                    if(this.show_hours_form == false) {
                        this.showHoursMessage = '@lang("package.package_for_hours")'
                        this.show_hours_form = true
                        // this.showButtonOrder = false
                        this.form.type_package = 2
                    } else {
                        this.showHoursMessage = '@lang("package.package_for_hours")'
                        // this.show_hours_form = false
                        // this.form.type_package = 0
                    }
                },
                deleteFormHours () {
                    // if(this.show_hours_form == false) {
                        {{--this.showHoursMessage = '@lang("package.package_for_hours")'--}}
                        this.show_hours_form = false
                        this.form.type_package = 0
                    // }
                },
                deleteFormOrder () {
                    // if(this.show_hours_form == false) {
                        {{--this.showHoursMessage = '@lang("package.package_for_hours_delete")'--}}
                        this.show_order_form = false
                        this.form.type_package = 1
                    // }
                },
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
                    <div class="card-body">
                        <div v-if="show_tow_form == true || show_hours_form == true">
                            @lang('store.packagePerHours')
                            <button v-if="!show_tow_form && form.type_package == 1" type="button" @click="changeFormOrder" class="btn btn-success">@{{showOrderMessage}}</button>&nbsp;
                            <button v-show="typeHoursDelete" type="button" @click="deleteFormHours" class="btn btn-danger"><span class="fa fa-trash"></span></button>
                            <div id="hoursHr" disabled="none" class="separator separator-dashed my-10"></div>
                            <div id="hours" disabled="none" class="form-group row">
                                <div class="col-lg-6 mb-6">
                                    <label for="exampleSelect1">@lang('store.date')
                                        <span class="text-danger">*</span></label>
                                    <div class="input-daterange input-group @error('start') is-invalid @enderror">
                                        <input v-model="form.mainPackageHours.start" autocomplete="off" min='{{now()->format('Y-m-d')}}' type="date" class="form-control"/>
                                        <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="la la-ellipsis-h"></i>
                                                                    </span>
                                        </div>
                                        <input v-model="form.mainPackageHours.end" autocomplete="off" min='{{now()->format('Y-m-d')}}' type="date" class="form-control" />
                                    </div>
                                    <span class="text-danger" v-if="validPackageHoursStart">@{{ validPackageHoursStart }} <br /></span>
                                    <span class="text-danger" v-if="validPackageHoursEnd">@{{ validPackageHoursEnd }}</span>
                                </div>
                                <div class="col-lg-3">
                                    <label>@lang('store.pricing')
                                        <span class="text-danger">*</span></label>
                                    <div class="input-group ">
                                        <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="30" height="20" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve"><g><g xmlns="http://www.w3.org/2000/svg" id="_1_toucan" data-name="1 toucan"><path d="m158.293 314.575a10 10 0 0 0 10-10v-56.745l13.833-15.15 53.492 77.572a10 10 0 0 0 16.465-11.352l-56.117-81.377 51.847-56.784a10 10 0 1 0 -14.769-13.486l-64.751 70.917v-64.176a10 10 0 0 0 -20 0v150.581a10 10 0 0 0 10 10z" fill="#000000" data-original="#000000"></path><path d="m367.424 164.681c-32.707-27.731-83.36-19.715-85.5-19.36a10 10 0 0 0 -8.364 9.865v148.2a10 10 0 0 0 8.364 9.866 127.779 127.779 0 0 0 19.353 1.342c18.237 0 45.7-3.365 66.148-20.7 17.44-14.787 26.283-36.523 26.283-64.6s-8.844-49.826-26.284-64.613zm-12.87 113.9c-18.742 15.944-47.049 16.577-60.995 15.751v-130.092c13.952-.826 42.256-.191 60.995 15.75 12.709 10.812 19.153 27.4 19.153 49.295s-6.444 38.482-19.153 49.294z" fill="#000000" data-original="#000000"></path><path d="m512.005 229.285c0-61.463-26.778-119.182-75.4-162.522-48.305-43.05-112.438-66.763-180.6-66.763-56.355 0-109.805 16.076-154.569 46.482a10 10 0 0 0 11.237 16.545c41.437-28.146 91-43.027 143.332-43.027 130.071 0 235.9 93.8 236 209.122a188.139 188.139 0 0 1 -3.421 35.837c-9.274 47.846-37.43 91.678-79.294 123.431-42.654 32.353-97.091 50.17-153.283 50.17s-110.637-17.817-153.292-50.17c-41.865-31.753-70.021-75.582-79.295-123.428-.011-.061-.022-.122-.034-.182a188.206 188.206 0 0 1 -3.379-35.5c0-52.878 22.305-103.361 62.807-142.148a10 10 0 0 0 -13.835-14.44c-44.479 42.596-68.974 98.208-68.974 156.593v53.429c0 61.464 26.777 119.182 75.4 162.522 48.295 43.05 112.434 66.764 180.6 66.764a280.86 280.86 0 0 0 95.365-16.431 10 10 0 1 0 -6.77-18.819 260.933 260.933 0 0 1 -88.6 15.25c-111.659 0-205.453-69.134-229.827-161.666a235.586 235.586 0 0 0 64.454 74c46.111 34.975 104.843 54.235 165.378 54.235s119.26-19.26 165.369-54.235a235.6 235.6 0 0 0 64.426-73.951 194.885 194.885 0 0 1 -24.535 55.651c-19.605 30.553-47.808 56.462-81.559 74.927a10 10 0 1 0 9.6 17.546c36.679-20.068 67.383-48.309 88.791-81.672a211.155 211.155 0 0 0 33.903-114.121s.005-52.66.005-53.429z" fill="#000000" data-original="#000000"></path><path d="m439.461 255.567c-6.946 35.352-28.287 68.108-60.1 92.24-34.07 25.838-77.879 40.067-123.357 40.067-34.09 0-67.494-8.011-96.6-23.169a10 10 0 1 0 -9.237 17.739c31.948 16.636 68.546 25.43 105.839 25.43 49.815 0 97.916-15.672 135.443-44.132 35.763-27.129 59.792-64.219 67.678-104.527l.071-.411a156.576 156.576 0 0 0 2.818-29.519c0-46.784-20.9-91.081-58.848-124.735a206.354 206.354 0 0 0 -20.1-15.683 10 10 0 0 0 -11.31 16.495 186.369 186.369 0 0 1 18.14 14.154c33.606 29.8 52.115 68.786 52.115 109.769a136.378 136.378 0 0 1 -2.442 25.661c-.037.192-.071.384-.11.621z" fill="#000000" data-original="#000000"></path><path d="m345.6 68.459a229.363 229.363 0 0 0 -89.594-17.765c-55.728 0-107.995 19.127-147.175 53.859-37.941 33.647-58.831 77.947-58.831 124.732a156.077 156.077 0 0 0 2.8 29.523c.011.058.022.118.034.183.017.1.035.2.055.3 7.407 37.862 29.418 73.363 61.977 99.964a10 10 0 0 0 12.654-15.488c-28.912-23.619-48.43-54.924-54.972-88.167-.021-.12-.043-.24-.066-.363-.01-.052-.02-.1-.029-.156a136 136 0 0 1 -2.453-25.796c0-40.989 18.5-79.972 52.1-109.768 35.517-31.483 83.073-48.823 133.907-48.823a209.453 209.453 0 0 1 81.826 16.2 10 10 0 0 0 7.767-18.435z" fill="#000000" data-original="#000000"></path></g></g></svg>
                                                    </span>
                                        </div>
                                        <input v-model.number="form.mainPackageHours.pricing" name="pricing" autocomplete="off" type="number" class="form-control @error('pricingh') is-invalid @enderror" placeholder="@lang('store.pricingPlaceholder')" />
                                    </div>
                                    <span class="text-danger" v-if="validPackageHoursPricing">@{{ validPackageHoursPricing }}</span>
                                </div>
                                <div class="col-lg-3">
                                    <label>@lang('store.drivers')
                                        <span class="text-danger">*</span></label>
                                    <div class="input-group ">
                                        <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="30" height="20" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve"><g><g xmlns="http://www.w3.org/2000/svg" id="_x32_4_Driver"><g><path d="m244.63 256.476c-2.023-2.132-5.391-2.205-7.507-.182-13.92 13.251-35.726 13.245-49.636 0-2.117-2.023-5.479-1.951-7.507.182-2.023 2.122-1.946 5.484.182 7.507 18.01 17.17 46.263 17.183 64.287 0 2.127-2.023 2.204-5.385.181-7.507z" fill="#000000" data-original="#000000"></path><path d="m241.19 202.685c2.687 1.141 5.821-.145 6.942-2.864 1.432-3.433 6.343-3.429 7.777 0 1.143 2.763 4.306 3.976 6.942 2.864 2.713-1.126 3.995-4.234 2.864-6.942-5.036-12.124-22.36-12.122-27.389 0-1.131 2.708.151 5.816 2.864 6.942z" fill="#000000" data-original="#000000"></path><path d="m183.648 202.685c2.708-1.136 3.985-4.244 2.854-6.952-5.071-12.108-22.342-12.154-27.389.01-1.131 2.708.15 5.816 2.864 6.942 2.637 1.113 5.799-.102 6.942-2.864 1.449-3.48 6.361-3.403 7.777.01 1.141 2.714 4.259 3.985 6.952 2.854z" fill="#000000" data-original="#000000"></path><path d="m418.41 310.217c-24.704 0-47.124 9.775-63.699 25.607-32.992-18.382-65.718-16.894-66.356-17-5.366-4.463-11.422-8.872-12.591-9.722-2.656-1.859-6.216-2.444-9.616-.903v-11.954c23.695-15.407 40.642-40.642 44.414-70.34l1.169-9.085c11.104-2.231 19.498-12.06 19.498-23.801 0-8.766-4.728-16.416-11.741-20.667 1.435-2.231 2.284-4.888 2.284-7.757 0-.531.213-38.942.213-41.28 18.169-8.394 27.52-19.976 27.52-35.011 0-38.783-62.796-71.562-137.121-71.562s-137.122 32.779-137.122 71.562c0 15.035 9.35 26.67 27.52 35.011 0 .531.212 41.333.212 41.333 0 2.816.85 5.472 2.284 7.757-7.013 4.25-11.688 11.847-11.688 20.613 0 11.741 8.394 21.57 19.498 23.801l1.169 9.085c3.825 29.698 20.773 54.933 44.467 70.34v11.954c-3.506-1.541-6.96-1.009-9.775.956-4.516 3.4-7.013 5.1-12.538 9.669h-1.009c-74.112 0-134.412 60.299-134.412 134.412v36.711c0 2.922 2.391 5.313 5.313 5.313h412.214c25.501 0 48.611-10.413 65.346-27.148 16.788-16.788 27.148-39.899 27.148-65.4-.001-51.002-41.546-92.494-92.601-92.494zm.053 32.567c27.413 0 50.577 18.595 57.643 43.777h-21.304c-5.685 0-10.944-1.966-14.344-5.472-5.844-5.95-13.654-9.191-21.942-9.191-6.588 0-12.857 2.072-18.063 5.897-5.525-8.075-11.847-15.513-18.966-22.154 10.2-8.023 23.004-12.857 36.976-12.857zm-44.68 20.135c6.853 6.375 12.963 13.547 18.223 21.304-2.869 1.487-6.216 2.338-9.829 2.338h-21.357c2.497-8.873 6.96-16.948 12.963-23.642zm-102.057.584c2.763-15.832 2.71-29.911.053-42.874l1.806.16c11.848 8.66 23.535 20.082 33.045 28.157-2.922 6.428-9.616 9.191-17.107 14.185-1.753 1.116-2.656 3.188-2.338 5.207 1.381 9.138 8.66 21.145 15.619 29.273-15.301 28.795-46.38 51.852-73.953 72.997 17.108-32.196 36.765-71.775 42.875-107.105zm-158.319-248.476c0-2.55 2.072-4.622 4.675-4.622h188.601c2.603 0 4.675 2.072 4.675 4.622 0 .585-.213 19.445-.213 19.445h-197.738zm197.739 36.764v12.804c0 2.284-1.859 4.144-4.144 4.144s-4.197-1.86-4.197-4.144v-6.906c2.922-1.86 5.685-3.826 8.341-5.898zm-189.239 5.897v6.96c0 2.284-1.859 4.144-4.144 4.144-2.284 0-4.144-1.86-4.144-4.144v-12.857c2.657 2.072 5.419 4.038 8.288 5.897zm2.869 66.834-1.7-13.229c-.319-2.656-2.604-4.675-5.26-4.675-7.491 0-13.601-6.11-13.601-13.601 0-6.96 4.888-13.229 13.866-13.654 7.969-.16 14.45-6.694 14.45-14.716v-.85c19.976 10.254 44.043 15.991 69.065 15.991h21.57c25.023 0 49.089-5.738 69.012-15.991v.797c0 8.022 6.482 14.557 14.451 14.77.159 0 .266.053.372.053 7.544 0 13.601 6.109 13.601 13.601 0 7.491-6.057 13.601-13.601 13.601-2.656 0-4.888 2.019-5.26 4.675l-1.7 13.229c-5.631 43.936-43.299 77.088-87.607 77.088-44.306-.001-82.026-33.153-87.658-77.089zm26.351 96.267 1.912-.16c-2.709 12.963-2.709 26.989.053 42.767 6.11 35.436 25.554 74.591 42.927 107.264-25.767-20.347-57.909-42.767-74.006-73.05 6.96-8.128 14.185-20.135 15.619-29.273.319-2.019-.638-4.091-2.338-5.207-7.385-4.888-14.185-7.756-17.107-14.238 9.989-8.446 21.04-19.337 32.94-28.103zm-139.512 163.844v-31.398c0-64.284 49.196-117.199 111.939-123.202-5.631 5.047-10.838 9.722-14.929 13.016-1.594 1.275-2.284 3.347-1.806 5.366 2.709 11.21 10.466 15.991 19.498 21.57-2.391 7.384-8.925 17.266-14.504 23.057-1.541 1.647-1.913 4.038-.957 6.003 16.523 34.108 51.533 59.396 85.907 85.588zm176.542-53.021c-8.447-17.691-14.504-32.833-18.807-46.539.797.213 1.966.797 3.931-.212l15.832-8.129c1.275 6.057 3.985 11.688 8.075 16.47zm-15.62-58.334-9.138-12.804c-2.391-14.45-2.284-27.201.319-38.942 3.56 1.062 6.854 2.975 9.563 5.631l30.229 30.229zm8.235-53.605c-3.294-3.294-7.225-5.738-11.422-7.438v-9.935c13.069 6.375 27.733 9.935 43.086 9.935 15.354 0 29.963-3.56 43.086-9.935v9.935c-4.25 1.7-8.182 4.144-11.476 7.438l-31.61 31.611zm70.819 7.491c2.656-2.656 5.897-4.516 9.51-5.631 2.55 11.688 2.71 24.492.319 38.995l-9.085 12.751-30.973-15.885zm-15.992 49.514 15.991 8.182c1.859.956 3.081.478 3.878.212-4.25 13.707-10.36 28.848-18.807 46.593l-9.032-38.411c4.039-4.834 6.748-10.466 7.97-16.576zm-7.544 107.955c32.248-24.651 69.225-51.161 85.907-85.588.956-1.966.584-4.356-.956-6.003-5.579-5.791-12.113-15.673-14.504-23.057 9.138-5.685 16.788-10.36 19.498-21.57.478-2.019-.213-4.091-1.86-5.366-4.303-3.56-9.191-7.916-14.822-12.963 16.416 1.594 31.93 6.375 45.849 13.707-13.229 16.045-21.198 36.552-21.198 58.918 0 35.542 20.135 66.409 49.567 81.922zm174.257-24.226c-20.029-5.631-35.861-21.41-41.492-41.492h22.26c10.519 0 19.232 8.501 19.232 19.126zm32.355 0v-22.26c0-10.519 8.501-19.232 19.126-19.232h22.313c-5.632 20.029-21.41 35.86-41.439 41.492zm43.405-52.118h-24.279c-16.416 0-29.751 13.335-29.751 29.857v24.279c-2.816.266-8.288.266-11.103 0v-24.385c0-16.31-13.229-29.751-29.857-29.751h-24.279c-.16-1.859-.266-3.719-.266-5.578 0-1.753.212-4.994.266-5.525h23.376c8.554 0 16.523-3.135 21.942-8.66 7.65-7.757 21.092-7.757 28.742.053 5.419 5.472 13.441 8.607 21.942 8.607h23.323c.16 1.806.266 3.666.266 5.525s-.11 3.719-.322 5.578z" fill="#000000" data-original="#000000"></path><path d="m432.595 402.711c0 7.81-6.322 14.079-14.078 14.079s-14.079-6.269-14.079-14.079c0-7.757 6.322-14.026 14.079-14.026s14.078 6.269 14.078 14.026z" fill="#000000" data-original="#000000"></path></g></g></g></svg>
                                                    </span>
                                        </div>
                                        <input @keyup="changeDriverNumber" v-model.number="form.mainPackageHours.drivers" name="driver_number" autocomplete="off" type="number" class="form-control @error('driver_number') is-invalid @enderror" placeholder="@lang('store.drivers')" />
                                    </div>
                                    <span class="text-danger" v-if="validPackageHoursDrivers">@{{ validPackageHoursDrivers }}</span>
                                </div>
                                <div class="col-lg-12" id="kt_repeater_1">
                                    <div v-for="(filed, key) in fileds" :key="key" class="form-group row">
                                        <div data-repeater-list="" class="col-lg-10">
                                            <div data-repeater-item="" class="form-group row align-items-center">

                                                <div class="col-md-2">
                                                    <div class="input-group timepicker">
                                                        <input v-model="filed.hours" class="form-control" @keyup="getDriverFunction(key)" placeholder="@lang('store.hour_of_work')" type="text" />
                                                    </div>
                                                    @if($errors->has('end_shift'))
                                                        <span class="text-danger">@lang('store.endHValidation')</span>
                                                    @endif
                                                </div>
                                                <div class="col-md-3">
                                                    <select :disabled="filed.hours === ''" v-model="filed.vehicle" name="drivers" @change="onChangeDrivers($event, key)" class="form-control @error('drivers') is-invalid @enderror">
                                                        <option value="">@lang('methodShippingTable.name')</option>
                                                        <option :selected="filed.vehicle == option.id" v-for="option in option_vehicle" :value="option.id">
                                                            @{{ option.name }}
                                                        </option>
                                                    </select>
                                                </div>

                                            </div>

                                        </div>

                                    </div>
                                    <div class="form-group row">
                                        <div id="roro"></div>
                                        <label class="col-lg-2 col-form-label text-right"></label>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div v-if="show_tow_form == true || show_order_form == true">
                            @lang('store.packagePerOrders')
                            <button type="button" @click="changeFormHours" v-if="!show_tow_form && form.type_package == 0" class="btn btn-success">@{{showHoursMessage}}</button> &nbsp;
                            <button v-show="typeOrderDelete" type="button" @click="deleteFormOrder" class="btn btn-danger"><span class="fa fa-trash"></span></button>
                            <div id="stockHr" disabled="none" class="separator separator-dashed my-10"></div>
                            <div id="stock" disabled="none" class="form-group row">
                                    <div class="col-lg-6 mb-5">
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
                                            <input autocomplete="off" v-model.number="form.packageOrder.stock" type="number" class="form-control @error('stock') is-invalid @enderror" name="stock" placeholder="@lang('store.stockPlaceholder')" />
                                        </div>
                                        <span class="text-danger" v-if="validPackageOrderStock">@{{ validPackageOrderStock }}</span>
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
                                            <input v-model.number="form.packageOrder.pricing" name="pricing" autocomplete="off" type="number" class="form-control @error('pricing') is-invalid @enderror" placeholder="@lang('store.pricingPlaceholder')" />
                                        </div>
                                        <span class="text-danger" v-if="validPackageOrderPricing">@{{ validPackageOrderPricing }}</span>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="exampleSelect1">@lang('store.date')
                                            <span class="text-danger">*</span></label>
                                        <div class="input-daterange input-group @error('startL_order') is-invalid @enderror" id="kt_datepicker_6">
                                            <input name="start" v-model="form.packageOrder.start" autocomplete="off" type="date" min='{{now()->format('Y-m-d')}}' class="form-control" />
                                            <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="la la-ellipsis-h"></i>
                                                                    </span>
                                            </div>
                                            <input autocomplete="off" v-model="form.packageOrder.end" name="end" type="date" min='{{now()->format('Y-m-d')}}' class="form-control" />
                                        </div>
                                        <span class="text-danger" v-if="validPackageOrderStart">@{{ validPackageOrderStart }} <br /></span>
                                        <span class="text-danger" v-if="validPackageOrderEnd">@{{ validPackageOrderEnd }}</span>
                                    </div>
                                </div>
                        </div>

{{--                            $keysArrayOrder = '';--}}
{{--                            $keysArrayHours = '';--}}
{{--                            $collection = collect($packageList);--}}
{{--                            $collection->contains(function($key, $value) use (&$keysArrayOrder, &$keysArrayHours){--}}
{{--                                if($key->number_of_order == null) {--}}
{{--                                    return $keysArrayHours = $value;--}}
{{--                                }--}}
{{--                                if($key->number_of_order !== null) {--}}
{{--                                    return $keysArrayOrder = $value;--}}
{{--                                }--}}
{{--                            });--}}
                        </div>
                    <div class="card-footer">
                        <button :disabled="display_btn" type="button" @click="sendData" class="btn btn-primary mr-2">@lang('form.update')</button>
                        <a href="{{Route('vendor.index')}}" class="btn btn-secondary">@lang('form.cancel')</a>
                    </div>
                </form>
                <!--end::Form-->
            </div>
            <!--end::Card-->
        </div>
    </div>
@endsection
