@extends('panel._layout')
@section('subTitle')
    @lang('sidebar.scheduling_drivers')
@endsection
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
    <script src="https://unpkg.com/vue@next"></script>
{{--    <script src="https://unpkg.com/vue@3.1.1/dist/vue.global.prod.js"></script>--}}
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        const AttributeBinding = {

            data() {
                return {
                    postScheduling: '{!! Route('drivers.scheduling.drivers.post') !!}', // route get delivery
                    getVendorsRoute: '{!! Route('drivers.scheduling.drivers.vue') !!}', // route get delivery
                    getDelivery: '{!! Route('get.drivers.admin') !!}', // route get delivery,
                    postSchedulingByDate: '{!! Route('drivers.scheduling.drivers.post.by.date') !!}', // route get delivery,
                    form: [{
                        fileds: []
                    }],
                    fileds: [],
                    genralData: [],
                    progress: '',
                    optionDrvier: [],
                    firstItems: 0,
                    rsave: null,
                }
            },
            created() {
            },
            mounted() {
                this.getVendors()
                this.getDriverFunction()
            },
            watch: {
                
            },
            computed: {
            },
            methods: {
                userInput(key) {
                    // here you change the rows key
                    console.log(key)
                },
                saveDriver() {
                    if(this.rsave) {
                        let myToken =  '{!! csrf_token() !!}'
                        // axios.defaults.headers.common = {
                        //     // 'X-Requested-With': 'XMLHttpRequest',
                        //     'Accept': 'application/json',
                        //     'X-CSRF-TOKEN': myToken
                        // };
                        let formDate = {data: this.rsave}
                        config = {'Accept': 'application/json'}
                        axios.post(this.postSchedulingByDate, formDate, config).then(response => {
                            // this.listDelivery = response.data.delivery
                            console.log(response.data.code)
                            if(response.data.code == 200) {
                                toastr.options = {
                                    "closeButton": false,
                                    "debug": false,
                                    "newestOnTop": false,
                                    "progressBar": true,
                                    "positionClass": "toast-bottom-right",
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
                                location.reload();
                            } else if(response.data.code == 404) { 
                                toastr.options = {
                                    "closeButton": true,
                                    "debug": false,
                                    "newestOnTop": true,
                                    "progressBar": false,
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
                                toastr.warning('@lang("alert.validDriverNotFoundThisDate")')
                            } else {
                                toastr.options = {
                                    "closeButton": true,
                                    "debug": false,
                                    "newestOnTop": true,
                                    "progressBar": false,
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
                                toastr.warning('@lang("alert.validDriver")')
                            }

                            //window.location.href = '{!! Route('vendor.index') !!}';
                        }).catch((error) => {
                            this.progress = false
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
                        });
                    } else {
                        toastr.options = {
                            "closeButton": false,
                            "debug": false,
                            "newestOnTop": true,
                            "progressBar": false,
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
                        toastr.warning('@lang("alert.errorMessage")')
                    }
                },
                getDriverFunction() {
                    // csrf token
                    let myToken =  '{!! csrf_token() !!}'
                    axios.defaults.headers.common = {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': myToken
                    };
                    // request post with data start time - end time
                    axios.post(this.getDelivery).then(response => {
                        // console.log(response.data.delivery)
                        this.optionDrvier = response.data.delivery
                        //this.fileds[index].option = response.data.delivery
                    })
                },
                onChangeDriver(event, index) {
                    this.fileds[index].drivers = event.target.value
                    // let statusId = event.target.value
                    // this.form.status = statusId
                },
                removeRows(index) {
                    this.fileds.splice(index, 1);
                },
                sendData(e) {
                    e.preventDefault();
                    // this.progress = true
                    // this.resetValidation()
                    let myToken =  '{!! csrf_token() !!}'
                    // axios.defaults.headers.common = {
                    //     // 'X-Requested-With': 'XMLHttpRequest',
                    //     'Accept': 'application/json',
                    //     'X-CSRF-TOKEN': myToken
                    // };
                    let cleanArray = this.fileds.filter(function () { return true });
                    // console.log(cleanArray)
                    // return
                    let form = {data: this.fileds}
                    config = {'Accept': 'application/json'}
                    axios.post(this.postScheduling,  cleanArray, config).then(response => {
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
                            {{--toastr.error('@lang("alert.errorMessage")')--}}
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
                                "hideMethod": "fadeOut",
                            }
                            toastr.success('@lang("alert.successCreated")')
                        }
                        location.reload();
                    }).catch((error) => {
                        this.progress = false
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
                        if(error.response.data.errors.email != undefined){
                            this.validEmail = error.response.data.errors.email[0];
                        }
                        //Logs a string: Error: Request failed with status code 404
                        if(error.response.data.errors['mainPackageHours.pricing'] != undefined){
                            this.validPricing = error.response.data.errors['mainPackageHours.pricing'][0];
                        }

                    });
                },
                clearForm() {
                    // this.fileds = [{'drivers': '',
                    //     'start_shift' : '',
                    //     'end_shift': '',
                    //     'option': []
                    // }]
                    this.form.nameAr = ''
                    this.form.nameEn = ''
                    this.form.phone = ''
                    this.form.email = ''
                    this.form.address = ''
                    this.form.package = ''
                    this.form.status = ''
                    this.form.password = ''
                    // this.form.packageHours.start_shift = ''
                    // this.form.packageHours.start_shift.end_shift = ''
                    // this.form.packageHours.start_shift.drivers = ''
                    this.form.packageOrder.pricing = ''
                    this.form.packageOrder.stock = ''
                    this.form.packageOrder.startL_order = ''
                    this.form.packageOrder.endL_order = ''
                    this.form.mainPackageHours = ''
                    this.form.mainPackageHours.start = ''
                    this.form.mainPackageHours.end = ''
                    this.form.mainPackageHours.pricing = ''
                    this.selectedIndex = ''
                },
                resetValidation() {
                    this.validEmail = ''
                    this.validPayments = ''
                    this.validNameAr = ''
                    this.validNameEn = ''
                    this.validPhone = ''
                    this.validPassword = ''
                    this.validPackage = ''
                    this.validStatus = ''
                    this.validLat = ''
                    this.validLong = ''
                    this.validPricing = ''
                    this.validStart = ''
                    this.validDriver = ''
                    this.validEndShift = ''
                    this.validStartShift = ''
                    this.validStartShiftTwo = ''
                    this.validStartStartLOrder = ''
                    this.validStartStock = ''
                },
                getVendors() {
                    let self = this
                    // trash data
                    this.genralData = []
                    // csrf token
                    let myToken =  '{!! csrf_token() !!}'
                    axios.defaults.headers.common = {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': myToken
                    };
                    // request post with data start time - end time
                    axios.post(this.getVendorsRoute).then(response => {
                        // this.listDelivery = response.data.delivery
                        this.genralData = response.data.data
                        console.log(response.data.data)
                        response.data.data.forEach((v, keys) => {
                            this.firstItems += v.time_delivery.length
                            v.time_delivery.forEach((value, index) => {
                                let obj = {}
                                let id_key = value.id
                                obj[value.id] = {
                                    'drivers': '',
                                    'tim_from_first' : '',
                                    'tim_to_first': '',
                                    'tim_from_secound': '',
                                    'tim_to_secound': '',
                                    'id': v.id,
                                }
                                
                                if(v.drivers[index]) {
                                    self.fileds[value.id]={
                                        'drivers': v.drivers[index].drivers.id,
                                        'tim_from_first' : v.drivers[index].time_first_from,
                                        'tim_to_first': v.drivers[index].time_first_to,
                                        'tim_from_secound': v.drivers[index].start_secound_shift,
                                        'tim_to_secound': v.drivers[index].end_secound_shift,
                                        'id': v.id,
                                    }
                                } else {
                                    self.fileds[value.id]={
                                        'drivers': '',
                                        'tim_from_first' : '',
                                        'tim_to_first': '',
                                        'tim_from_secound': '',
                                        'tim_to_secound': '',
                                        'id': v.id,
                                    }
                                }

                            });
                        });
                        this.firstItems -= 1
                        // this.fileds[index].option = response.data.delivery
                    })

                },
            },
        }
        Vue.createApp(AttributeBinding).mount('#app')

        $( document ).ready(function() {
            $( "#hohose" ).click(function() {
                $('#hohose').attr('disabled', true);
                !$("#driver_form").submit();
            });
        });
    </script>

@endpush
@push('style')
    <style>
        #toast-container > .toast {
            background-image: none !important;
        }
    </style>
@endpush
@section('pageTitle')
    <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
    <span class="text-muted font-weight-bold mr-4">@lang('sidebar.scheduling_drivers')</span>
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
                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="35px" height="24px" x="0" y="0" viewBox="0 0 62 62" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><g xmlns="http://www.w3.org/2000/svg"><g><g><path d="m58 32c-1.46-1.46-3.31-2.43-5.3-2.81-.43 1.5-1.28 2.81-2.41 3.81 1.77.03 3.46.75 4.71 2 1.28 1.28 2 3.02 2 4.83v3.17h-22v-9c0-.55.45-1 1-1h1.71c-1.18-1.04-2.05-2.43-2.46-4h-.25c-2.21 0-4 1.79-4 4v10h-28c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h58v-15.76c0-2.71-1.08-5.32-3-7.24z" fill="#FCD770" data-original="#fcd770" class=""></path></g><g><path d="m37 11c.59-2.35 2.7-4 5.12-4h5.69c5.07 0 9.19 4.12 9.19 9.19 0 1.86-.56 3.64-1.59 5.16-.42-.22-.9-.35-1.41-.35h-1v-2c-1.94-.65-3.62-1.93-4.75-3.63l-.25-.37-5.32 2.66c-1.76.88-3.71 1.34-5.68 1.34-.55 0-1.05-.22-1.41-.59-.37-.36-.59-.86-.59-1.41v4h-1c-.65 0-1.26.21-1.75.56-.8-1.12-1.25-2.48-1.25-3.89 0-1.85.74-3.51 1.95-4.72 1.06-1.06 2.47-1.76 4.05-1.92z" fill="#656D78" data-original="#656d78"></path></g><g><path d="m52 39 5 4h-7.12c1.17 0 2.12-.95 2.12-2.12v-.76c0-.33-.07-.64-.21-.91z" fill="#69D6F4" data-original="#69d6f4"></path></g><g><path d="m43 35h2c2.03 0 3.88-.76 5.29-2 1.77.03 3.46.75 4.71 2 1.28 1.28 2 3.02 2 4.83v3.17l-5-4-.21.21c-.1-.22-.24-.42-.41-.59-.38-.38-.92-.62-1.5-.62-.56 0-1.1.22-1.5.62l-.96.97h-.01c-.36-.37-.85-.59-1.41-.59h-1v-1c0-.83-.34-1.58-.88-2.12-.42-.43-.97-.72-1.94-.87v-.05c.27.03.54.04.82.04z" fill="#69D6F4" data-original="#69d6f4"></path></g><g><path d="m42.18 34.96v.05c-.06-.01-.12-.01-.18-.01-1.66 0-3 1.34-3 3v3c0 .73.2 1.41.54 2h-4.54v-9c0-.55.45-1 1-1h1.71c1.22 1.08 2.76 1.79 4.47 1.96z" fill="#69D6F4" data-original="#69d6f4"></path></g><g><path d="m48 43h-8.46c-.34-.59-.54-1.27-.54-2v-3c0-1.66 1.34-3 3-3 .06 0 .12 0 .18.01.97.15 1.52.44 1.94.87.54.54.88 1.29.88 2.12v1h1c.56 0 1.05.22 1.41.59h.01c.36.36.58.86.58 1.41z" fill="#FF826E" data-original="#ff826e"></path></g><g><path d="m51.79 39.21c.14.27.21.58.21.91v.76c0 1.17-.95 2.12-2.12 2.12h-1.88v-2c0-.55-.22-1.05-.58-1.41l.96-.97c.4-.4.94-.62 1.5-.62.58 0 1.12.24 1.5.62.17.17.31.37.41.59z" fill="#F0D0B4" data-original="#f0d0b4" class=""></path></g><g><path d="m55.41 21.35c.95.5 1.59 1.5 1.59 2.65 0 .83-.34 1.58-.88 2.12s-1.29.88-2.12.88h-1v-6h1c.51 0 .99.13 1.41.35z" fill="#F0D0B4" data-original="#f0d0b4" class=""></path></g><g><path d="m37 19c1.97 0 3.92-.46 5.68-1.34l5.32-2.66.25.37c1.13 1.7 2.81 2.98 4.75 3.63v2 6c0 .76-.11 1.49-.3 2.19-.43 1.5-1.28 2.81-2.41 3.81-1.41 1.24-3.26 2-5.29 2h-2c-.28 0-.55-.01-.82-.04-1.71-.17-3.25-.88-4.47-1.96-1.18-1.04-2.05-2.43-2.46-4-.16-.64-.25-1.31-.25-2v-6-4c0 .55.22 1.05.59 1.41.36.37.86.59 1.41.59z" fill="#F0D0B4" data-original="#f0d0b4" class=""></path></g><g><path d="m35 21v6h-1c-1.66 0-3-1.34-3-3 0-.83.34-1.58.88-2.12.11-.11.24-.22.37-.32.49-.35 1.1-.56 1.75-.56z" fill="#F0D0B4" data-original="#f0d0b4" class=""></path></g><g><path d="m3 23h24v20h-24z" fill="#D3A06C" data-original="#d3a06c" class=""></path></g><g><path d="m9 23h12v8h-12z" fill="#B27946" data-original="#b27946"></path></g><g><path d="m25 10.91c0 2.01-.61 3.98-1.76 5.63l-4.47 6.46-2.2 3.18c-.36.51-.94.82-1.57.82s-1.21-.31-1.57-.82l-2.2-3.18-4.47-6.46c-1.15-1.65-1.76-3.62-1.76-5.63 0-5.48 4.43-9.91 9.91-9.91h.18c2.74 0 5.22 1.11 7.01 2.9s2.9 4.27 2.9 7.01z" fill="#B4DD7F" data-original="#b4dd7f"></path></g><g><circle cx="15" cy="11" fill="#FFEAA7" r="6" data-original="#ffeaa7"></circle></g><g><path d="m61 47v4h-5l-1-4z" fill="#FF826E" data-original="#ff826e"></path></g><g><path d="m7 47-1 4h-5v-4z" fill="#FF826E" data-original="#ff826e"></path></g><g><path d="m47 49c3.31 0 6 2.69 6 6s-2.69 6-6 6-6-2.69-6-6 2.69-6 6-6z" fill="#969FAA" data-original="#969faa"></path></g><g><path d="m15 49c3.31 0 6 2.69 6 6s-2.69 6-6 6-6-2.69-6-6 2.69-6 6-6z" fill="#969FAA" data-original="#969faa"></path></g><g><circle cx="47" cy="55" fill="#CCD1D9" r="2" data-original="#ccd1d9"></circle></g><g><circle cx="15" cy="55" fill="#CCD1D9" r="2" data-original="#ccd1d9"></circle></g></g><g><path d="m15 52c-1.654 0-3 1.346-3 3s1.346 3 3 3 3-1.346 3-3-1.346-3-3-3zm0 4c-.552 0-1-.448-1-1s.448-1 1-1 1 .448 1 1-.448 1-1 1z" fill="#000000" data-original="#000000"></path><path d="m47 52c-1.654 0-3 1.346-3 3s1.346 3 3 3 3-1.346 3-3-1.346-3-3-3zm0 4c-.552 0-1-.448-1-1s.448-1 1-1 1 .448 1 1-.448 1-1 1z" fill="#000000" data-original="#000000"></path><path d="m44 30c-1.103 0-2-.897-2-2h-2c0 2.206 1.794 4 4 4s4-1.794 4-4h-2c0 1.103-.897 2-2 2z" fill="#000000" data-original="#000000"></path><path d="m41 24h2c0-1.654-1.346-3-3-3s-3 1.346-3 3h2c0-.552.448-1 1-1s1 .448 1 1z" fill="#000000" data-original="#000000"></path><path d="m45 24h2c0-.552.448-1 1-1s1 .448 1 1h2c0-1.654-1.346-3-3-3s-3 1.346-3 3z" fill="#000000" data-original="#000000"></path><path d="m62 39.242c0-3.003-1.17-5.826-3.293-7.949-1.353-1.353-3.01-2.323-4.834-2.847.024-.148.052-.295.068-.446h.059c2.206 0 4-1.794 4-4 0-1.145-.49-2.173-1.264-2.902.826-1.501 1.264-3.178 1.264-4.906 0-5.62-4.572-10.192-10.192-10.192h-5.685c-2.665 0-4.986 1.677-5.881 4.138-3.548.67-6.242 3.789-6.242 7.529 0 1.322.35 2.591.988 3.729-.607.702-.988 1.605-.988 2.604 0 2.206 1.794 4 4 4h.059c.004.032.01.062.014.094-2.314.436-4.073 2.466-4.073 4.906v9h-2v-20h-7.322l3.383-4.887c1.268-1.831 1.939-3.978 1.939-6.208 0-6.012-4.893-10.905-10.905-10.905h-.189c-6.013 0-10.906 4.893-10.906 10.905 0 2.229.671 4.377 1.939 6.208l3.383 4.887h-7.322v20.184c-1.161.414-2 1.514-2 2.816v8c0 1.654 1.346 3 3 3h5.08c.488 3.386 3.401 6 6.92 6s6.432-2.614 6.92-6h18.16c.488 3.386 3.401 6 6.92 6s6.432-2.614 6.92-6h8.08zm-8-13.242v-4c1.103 0 2 .897 2 2s-.897 2-2 2zm-16.333-14h5.333v-2h-4.494c.767-1.219 2.114-2 3.617-2h5.685c4.517 0 8.192 3.675 8.192 8.192 0 1.39-.368 2.732-1.033 3.939-.311-.077-.632-.131-.967-.131v-1.721l-.684-.228c-1.726-.576-3.23-1.726-4.238-3.237l-.734-1.104-6.107 3.053c-1.617.809-3.427 1.237-5.236 1.237-.552 0-1-.448-1-1s.448-1 1-1h1v-2h-1c-1.654 0-3 1.346-3 3v3h-.001c-.483 0-.941.099-1.371.257-.411-.797-.629-1.678-.629-2.59 0-3.125 2.542-5.667 5.667-5.667zm-1.667 22h1.356c.563.455 1.185.838 1.847 1.148-.74.726-1.202 1.735-1.202 2.852v2.999c0 .338.045.672.112 1.001h-2.113zm4.195 8c-.115-.322-.194-.655-.194-1.001v-2.999c0-1.103.897-2 1.999-2 1.104.001 2.001.898 2 2.001v1.999h2.001c.552 0 1 .448 1 1v1zm8.505-2.286.386-.386c.209-.209.498-.328.793-.328.618 0 1.121.503 1.121 1.121v.758c0 .618-.503 1.121-1.121 1.121h-.878v-1c0-.462-.114-.895-.301-1.286zm4.281 1.352 1.167.934h-1.365c.113-.293.178-.607.198-.934zm3.019-.147-2.503-2.002 1.21-1.21c.391-.391.391-1.023 0-1.414s-1.023-.391-1.414 0l-1.435 1.435c-.54-.448-1.224-.728-1.979-.728-.822 0-1.626.333-2.207.914l-.386.386c-.391-.187-.823-.3-1.285-.3h-.001c0-.725-.205-1.414-.565-2.022 1.944-.094 3.728-.801 5.159-1.941 1.396.101 2.702.673 3.699 1.67 1.085 1.085 1.707 2.587 1.707 4.121zm-4-13.919c0 3.859-3.141 7-7.001 7h-1.999c-3.859 0-7-3.141-7-7.001l.001-7.171c.313.111.649.172 1 .172 2.117 0 4.237-.501 6.13-1.447l4.536-2.268c1.102 1.506 2.611 2.691 4.333 3.407zm-20-3c0-1.103.897-2 2-2v4c-1.103 0-2-.897-2-2zm-26-13.095c0-4.91 3.995-8.905 8.905-8.905h.189c4.911 0 8.906 3.995 8.906 8.905 0 1.821-.548 3.573-1.584 5.069l-6.669 9.634c-.34.49-1.154.49-1.494 0l-6.669-9.634c-1.036-1.495-1.584-3.247-1.584-5.069zm6.608 15.842c.544.784 1.438 1.253 2.392 1.253s1.848-.469 2.392-1.253l1.901-2.747h.707v6h-10v-6h.707zm-4.608-2.747v8h14v-8h4v18h-22v-18zm-6 24h3.719l-.5 2h-3.219zm0 5v-1h4.781l1.5-6h-6.281v-1c0-.552.448-1 1-1h27v10h-8.08c-.488-3.386-3.401-6-6.92-6s-6.432 2.614-6.92 6h-5.08c-.552 0-1-.448-1-1zm13 7c-2.757 0-5-2.243-5-5s2.243-5 5-5 5 2.243 5 5-2.243 5-5 5zm32 0c-2.757 0-5-2.243-5-5s2.243-5 5-5 5 2.243 5 5-2.243 5-5 5zm6.92-6c-.488-3.386-3.401-6-6.92-6s-6.432 2.614-6.92 6h-8.08v-21c0-1.498 1.106-2.731 2.542-2.954.258.714.595 1.389 1.016 2.007-.89.202-1.558.997-1.558 1.947v10h14.001 1.878 8.121v-4.172c0-2.061-.836-4.078-2.293-5.535-.969-.969-2.153-1.646-3.443-2.002.428-.585.79-1.22 1.067-1.9 1.492.433 2.853 1.209 3.961 2.317 1.747 1.745 2.708 4.065 2.708 6.534v6.758h-6.281l1.5 6h4.781v2zm6.08-6v2h-3.219l-.5-2z" fill="#000000" data-original="#000000"></path><path d="m22 11c0-3.859-3.141-7-7-7s-7 3.141-7 7 3.141 7 7 7 7-3.141 7-7zm-12 0c0-2.757 2.243-5 5-5s5 2.243 5 5-2.243 5-5 5-5-2.243-5-5z" fill="#000000" data-original="#000000"></path><path d="m10 38h2v2h-2z" fill="#000000" data-original="#000000"></path><path d="m6 38h2v2h-2z" fill="#000000" data-original="#000000"></path></g></g></g></svg>
                    </h3>
                        <div class="card-toolbar">
                            <svg @click="saveDriver" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="35px" height="24px" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve"><g><g xmlns="http://www.w3.org/2000/svg"><path d="m441.843 80.109 31.07-2.608-2.509-29.895-84.979 7.132 7.133 84.979 29.895-2.509-3.124-37.224c39.937 41.746 62.374 97.104 62.374 155.866 0 60.327-23.493 117.043-66.15 159.701s-99.374 66.15-159.701 66.15-117.044-23.491-159.702-66.148-66.15-99.374-66.15-159.701 23.493-117.044 66.15-159.702 99.374-66.15 159.702-66.15v-30c-68.34 0-132.59 26.613-180.914 74.938-48.325 48.323-74.938 112.573-74.938 180.914s26.613 132.59 74.938 180.914c48.324 48.324 112.574 74.938 180.914 74.938s132.59-26.613 180.914-74.938c48.324-48.324 74.938-112.574 74.938-180.914-.001-66.177-25.115-128.548-69.861-175.743z" fill="#FFE705" data-original="#ffe705"></path><circle cx="255.852" cy="255.852" fill="#F9F9F9" r="143.74" data-original="#f9f9f9"></circle><path d="m255.852 112.111v287.481c79.386 0 143.74-64.355 143.74-143.74s-64.355-143.741-143.74-143.741z" fill="#ECEAE7" data-original="#eceae7"></path><g><path d="m240.852 112.111h30v43.013h-30z" fill="#E7DFDD" data-original="#e7dfdd"></path></g><path d="m255.852 112.111h15v43.013h-15z" fill="#DAD0CB" data-original="#dad0cb"></path><g><path d="m240.852 356.579h30v43.013h-30z" fill="#E7DFDD" data-original="#e7dfdd"></path></g><path d="m255.852 356.579h15v43.013h-15z" fill="#DAD0CB" data-original="#dad0cb"></path><g><path d="m356.579 240.852h43.013v30h-43.013z" fill="#E7DFDD" data-original="#e7dfdd"></path></g><g><path d="m356.579 240.852h43.013v30h-43.013z" fill="#DAD0CB" data-original="#dad0cb"></path></g><g><path d="m112.111 240.852h43.013v30h-43.013z" fill="#E7DFDD" data-original="#e7dfdd"></path></g><path d="m255.852 414.592c-87.53 0-158.74-71.21-158.74-158.74s71.21-158.74 158.74-158.74 158.74 71.21 158.74 158.74-71.211 158.74-158.74 158.74zm0-287.481c-70.988 0-128.74 57.752-128.74 128.74s57.752 128.74 128.74 128.74 128.74-57.752 128.74-128.74-57.753-128.74-128.74-128.74z" fill="#7DD5F4" data-original="#7dd5f4"></path><g><path d="m219.905 305.702-19.316-22.955 40.263-33.877v-74.519h30v88.482z" fill="#454545" data-original="#454545"></path></g><path d="m255.852 97.111v30c70.988 0 128.74 57.752 128.74 128.74s-57.752 128.74-128.74 128.74v30c87.53 0 158.74-71.21 158.74-158.74s-71.211-158.74-158.74-158.74z" fill="#6BA7FF" data-original="#6ba7ff"></path><path d="m270.852 174.351h-15v101.104l15-12.622z" fill="#2E2E2E" data-original="#2e2e2e"></path><path d="m441.843 80.109 31.07-2.608-2.509-29.895-84.979 7.132 7.133 84.979 29.895-2.509-3.124-37.224c39.937 41.746 62.374 97.104 62.374 155.866 0 60.327-23.493 117.043-66.15 159.701s-99.374 66.15-159.701 66.15v30c68.34 0 132.59-26.613 180.914-74.938 48.324-48.324 74.938-112.574 74.938-180.914-.001-66.174-25.115-128.545-69.861-175.74z" fill="#ECBE00" data-original="#ecbe00"></path></g></g></svg>

                            <div class="col-lg-10">
                                <input max='{{now()->format('Y-m-d')}}' v-model="rsave" type="date" class="form-control">
                            </div>
                        </div>

                </div>
                <!--begin::Form-->
                <form method="post" id="kt_form_1" action="{{Route('vendor.store')}}">
                    <div class="card-body">
                        <div v-for="(vendor, keys) in genralData" class="form-group row">
                            <div class="col-lg-12 mb-5">
                                <label for="exampleSelect1">@lang('store.name') :

                                </label>
																<span class="font-weight-bolder label label-xl label-light-success label-inline px-3 py-5 min-w-45px">
																	@{{vendor.user.name}}
																</span>
                                @error('type_order')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div v-for="(hours, index) in vendor.time_delivery" id="repeter" class="row col-lg-12 mb-5">
                                <div class="col-lg-2">
                                    <label for="exampleSelect1">@lang('store.drivers')
                                        <span class="text-danger">*</span></label>
                                    <select v-if="fileds[hours.id].optionDrvier != ''" @change="onChangeDriver($event, hours.id)" name="type_order" class="form-control @error('type_order') is-invalid @enderror" id="package">
                                        <option value="">--</option>
                                        <option v-for="option in optionDrvier" :selected="fileds[hours.id].drivers == option.id" :value="option.id">@{{ option.name }}</option>
                                    </select>
                                    @error('type_order')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-lg-2">
                                       <label for="exampleSelect1">@lang('store.hour_of_work')</label>
                                    <input style="background: #c9f7f5;font-weight: bold;color: #1bc5bd;padding-right: 43%;" type="text" disabled :value="hours.hours"
                                           class="form-control">
                                       @error('type_order')
                                       <span class="text-danger">{{ $message }}</span>
                                       @enderror
                                   </div>
                                <div class="col-lg-2">
                                    <label for="exampleSelect1">@lang('store.startShift')
                                        <span class="text-danger">*</span></label>
                                    <input v-model="fileds[hours.id].tim_from_first" type="time" class="form-control">
                                    @error('type_order')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-lg-2">
                                       <label for="exampleSelect1">@lang('store.endShift')
                                           <span class="text-danger">*</span></label>
                                       <input v-model="fileds[hours.id].tim_to_first" type="time" class="form-control">
                                       @error('type_order')
                                       <span class="text-danger">{{ $message }}</span>
                                       @enderror
                                </div>
                                <div class="col-lg-2">
                                       <label for="exampleSelect1">@lang('store.startShift')</label>
                                       <input v-model="fileds[hours.id].tim_from_secound" type="time" class="form-control">
                                       @error('type_order')
                                       <span class="text-danger">{{ $message }}</span>
                                       @enderror
                                   </div>
                                <div class="col-lg-2">
                                       <label for="exampleSelect1">@lang('store.endShift')</label>
                                       <input v-model="fileds[hours.id].tim_to_secound" type="time" class="form-control">
                                       @error('type_order')
                                       <span class="text-danger">{{ $message }}</span>
                                       @enderror
                                   </div>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-10"></div>
                    </div>
                    <div class="card-footer">
                        <button type="button" @click="sendData" class="btn btn-primary mr-2">@lang('form.save')</button>
                        <a href="{{Route('delivery.index')}}" class="btn btn-secondary">@lang('form.cancel')</a>
                    </div>
                </form>
                <!--end::Form-->
            </div>
            <!--end::Card-->
        </div>
    </div>
@endsection
