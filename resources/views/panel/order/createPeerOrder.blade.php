@extends('panel._layout')
@section('subTitle')
    @lang('navbar.createOrder')
@endsection

@push('style')
    <style>
        label#phone-error {
            color: red;
        }
        .pac-container {

            z-index : 200000;
        }

        #map-canvas {
            height: 75%;
            width: 75%;
        }


    </style>
@endpush
@push('js')


    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script
        src="{{"https://maps.googleapis.com/maps/api/js?key=AIzaSyDQDC3VV5CGRaueUYpEEJ308KNx8zbG5t0&language=".app()->getLocale()."&libraries=places"}}"
        type="text/javascript">
    </script>
    <script src="https://unpkg.com/vue@next"></script>
    {{--    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <!-- Async script executes immediately and must be after any DOM elements used in callback. -->
    <script type="text/javascript">
        const AttributeBinding = {

            data() {
                return {
                    postOrder: '{!! Route('vendor.storeApi.peer.order') !!}',
                    form: {
                    }, // form data
                    showField: true,
                    getDelivery: '{!! Route('get_drivers_vue') !!}', // route get delivery
                    postDelivery: '{!! Route('vendor.storeApi') !!}', // route post data
                    getVendor: '{!! Route('vendor.get.vendor') !!}',
                    maxOrder: '{!! $activePackage->number_of_order !!}',
                    listDelivery: [], // list delivery
                    //selectedDriver: '',
                    selectedIndex: '',
                    disableAddOrder: true,
                    fileds: [{
                        'showField': true,
                        'type_order': '',
                        'latFrom' : '',
                        'longFrom': '',
                        'cityFrom': '',
                        'latTo' : '',
                        'longTo': '',
                        'cityTo': '',
                        'order_reference': '',
                        'name': '',
                        'mobile': '',
                        'date': '',
                        'driver': '',
                        'about': '',
                        'validFrom': false,
                        'validTo': false,
                        'validName': false,
                        'validMobile': false,
                        'validMobileMax': false,
                        'validMobileMin': false,
                        'validDate': false,
                        'validTimeFroms': false,
                        'validTimeFromsf': false,
                        'validTimetof': false,
                        'home': '',
                        'sabil': '',
                        'street': '',
                        'block': '',
                        'validHome': false,
                        'validSabil': false,
                        'validStreet': false,
                        'validBlock': false,
                        'validTypeOrder': false,
                        
                        
                        'items': '',
                        'payment_method': '',
                        'totale_amount': '',
                        'city_id': '',
                        'optionsCity': [],
                        
                        
                        'show_validation_time_from': false,
                        'show_validation_time_to': false,
                        
                        
                        
                        'date_from': '',
                        'time_from': '',
                        'validTimeFrom': false,
                        'date_to': '',
                        'time_to': '',
                        'validTimeTo': false,
                        'from_date_front': '',
                    }],
                    vendor_id: '',
                    prosscing: false,
                    latAuth: '{!! Auth::user()->lat !!}',
                    longAuth: '{!! Auth::user()->long !!}',
                    cityAuth: '{!! Auth::user()->address !!}',
                    order_peer_hours: false,
                    order_peer_order: false,
                        optionsArea: [],
                    getArea: '{!! Route('get_rea') !!}',
                }
            },
            created() {
                // this.getDriverFunction()
                this.getAreaf()
            },
            mounted() {
                this.getVendorFunction()
            },
            watch: {
                fileds:{
                    handler: function(val) {
                        console.log(val)
                    }
                }
            },
            computed: {
            },
            methods: {
                updateValidationFrom(index) {
                    var d = new Date();
                    if(this.fileds[index].date_from === ''){
                        this.fileds[index].validTimeFrom = true // form
                        this.prosscing = true // form
                        this.fileds[index].time_from = ''
                        return 0;
                    }
                    if(this.fileds[index].date_from == (new Date()).toISOString().split('T')[0]) {
                        var dt = new Date();
                        var stt = new Date((dt.getMonth() + 1) + "/" + dt.getDate() + "/" + dt.getFullYear() + " " + this.fileds[index].time_from);

                        stt = stt.getTime();
                        // if(dt.getHours() + ":" + m < this.fileds[index].time_from) {
                        if(dt.getTime() < stt) {
                            this.prosscing = false // form
                            this.fileds[index].validTimeFrom = false
                            this.fileds[index].show_validation_time_from = false
                        } else {
                            this.prosscing = true // form
                            this.fileds[index].show_validation_time_from = true
                            this.fileds[index].validTimeFrom = false
                        }
                    } else {
                        this.fileds[index].validTimeFrom = false // form
                        this.prosscing = false // form
                        this.fileds[index].show_validation_time_from = false
                    }
                },
                updateValidationTo(index) {
                    
                    var d_to = new Date();
                    if(this.fileds[index].date_to === ''){
                        this.fileds[index].validTimeTo = true // form
                        this.prosscing = true // form
                        this.fileds[index].time_to = ''
                        return 0;
                    }
                    if(this.fileds[index].date_to == (new Date()).toISOString().split('T')[0]) {
                        var dt_to = new Date();
                        var stt_to = new Date((dt_to.getMonth() + 1) + "/" + dt_to.getDate() + "/" + dt_to.getFullYear() + " " + this.fileds[index].time_to);

                        stt_to = stt_to.getTime();
                        // if(dt.getHours() + ":" + m < this.fileds[index].time_from) {
                        if(dt_to.getTime() < stt_to) {
                            this.prosscing = false // form
                            this.fileds[index].validTimeTo = false
                            this.fileds[index].show_validation_time_to = false
                        } else {
                            this.prosscing = true // form
                            this.fileds[index].show_validation_time_to = true
                            this.fileds[index].validTimeTo = false
                        }
                    } else {
                        this.fileds[index].validTimeTo = false // form
                        this.prosscing = false // form
                        this.fileds[index].show_validation_time_to = false
                    }
                },
                getAreaf(){
                    axios.get(this.getArea).then(response => {
                        this.optionsArea = response.data.data
                    })
                },
                onChangeArea(event, inde) {
                this.fileds[inde].optionsCity = event.target.value
                
                this.fileds[inde].optionsCity = ''
                this.optionsArea.filter((val, index) => {
                if(val.id == event.target.value) {
                    this.fileds[inde].optionsCity = ''
                    this.fileds[inde].optionsCity = val.cities
                    }
                });
                },
                onChangeCity(event, index) {
                    this.fileds[index].city_id = event.target.value
                },
                onChangePaymentType(event, index) {
                    this.fileds[index].payment_method = event.target.value
                },
                getDriverFunction(index) {
                    // csrf token
                    let myToken =  '{!! csrf_token() !!}'
                    axios.defaults.headers.common = {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': myToken
                    };

                    // request post with data start time - end time
                    axios.post(this.getDelivery, formData).then(response => {
                        // this.listDelivery = response.data.delivery
                        this.fileds[index].option = response.data.delivery
                    })
                },
                onChangeTypeOrder(event, index) {
                    this.fileds[index].validTypeOrder = false
                    this.prosscing = false
                    if(event.target.value == '1') {
                        this.fileds[index].showField = false
                        this.fileds[index].type_order = event.target.value
                        $('#latHiddenFrom' + index).val('')
                        $('#longHiddenFrom' + index).val('')
                        $('#exampleModalLabelfromf' + index).text('')
                    } else if (event.target.value == '0') {
                        $('#latHiddenFrom' + index).val(this.latAuth)
                        $('#longHiddenFrom' + index).val(this.longAuth)
                        $('#exampleModalLabelfromf' + index).text(this.cityAuth)
                        this.fileds[index].showField = false
                        this.fileds[index].type_order = event.target.value
                    } else {
                        this.fileds[index].showField = true
                    }
                },
                addRows() {
                    // maxOrder
                    // disableAddOrder
                    if(this.fileds.length >= this.maxOrder) {
                        this.disableAddOrder = false
                        return 0;
                    }
                    this.fileds.push({
                        'showField': true,
                        'type_order': '',
                        'latFrom' : '',
                        'longFrom': '',
                        'cityFrom': '',
                        'latTo' : '',
                        'longTo': '',
                        'cityTo': '',
                        
                        'order_reference': '',
                        'items': '',
                        'payment_method': '',
                        'totale_amount': '',
                        'city_id': '',
                        'optionsCity': [],
                        
                        'name': '',
                        'mobile': '',
                        'date': '',
                        'driver': '',
                        'about': '',
                        'validFrom': false,
                        'validTo': false,
                        'validName': false,
                        'validMobile': false,
                        'validMobileMax': false,
                        'validMobileMin': false,
                        'validDate': false,
                        'validTimeFroms': false,
                        'validTimeFromsf': false,
                        'validTimetof': false,
                        'from_date_front': '',
                        'home': '',
                        'sabil': '',
                        'street': '',
                        'block': '',
                        'validHome': false,
                        'validSabil': false,
                        'validStreet': false,
                        'validBlock': false,
                        'validTypeOrder': false,
                        
                        'date_from': '',
                        'time_from': '',
                        'validTimeFrom': false,
                        'date_to': '',
                        'time_to': '',
                        'validTimeTo': false,
                    })
                },
                removeRows(index) {
                    if(this.fileds.length <= this.maxOrder) {
                        this.disableAddOrder = true
                    }
                    if(index == 0) {
                        return 0;
                    }
                    this.fileds.splice(index, 1);

                },
                resetRepeter() {
                    this.prosscing = false
                    this.disableAddOrder = true
                    this.fileds = [{
                        'showField': true,
                        'type_order': '',
                        'latFrom' : '',
                        'longFrom': '',
                        'cityFrom': '',
                        'latTo' : '',
                        'longTo': '',
                        'cityTo': '',
                        'name': '',
                        'mobile': '',
                        'date': '',
                        
                        
                        
                        'items': '',
                        'payment_method': '',
                        'totale_amount': '',
                        'city_id': '',
                        'optionsCity': [],
                        'order_reference': '',
                        
                        'driver': '',
                        'about': '',
                        'validTimeFroms': false,
                        'validTimeFromsf': false,
                        'validTimetof': false,
                        'from_date_front': '',
                        'validFrom': false,
                        'validTo': false,
                        'validName': false,
                        'validMobile': false,
                        'validMobileMax': false,
                        'validMobileMin': false,
                        'validDate': false,
                        'home': '',
                        'sabil': '',
                        'street': '',
                        'block': '',
                        'validHome': false,
                        'validSabil': false,
                        'validStreet': false,
                        'validBlock': false,
                        'validTypeOrder': false,
                        
                        'date_from': '',
                        'time_from': '',
                        'validTimeFrom': false,
                        'date_to': '',
                        'time_to': '',
                        'validTimeTo': false,
                    }]
                },
                sendData() {
                    // this.prosscing = true
                    let myToken =  '{!! csrf_token() !!}'
                    axios.defaults.headers.common = {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': myToken
                    };
                    this.filterFields()
                    if(this.prosscing == true) {
                        return 0
                    }
                    if(this.fileds.length > 0) {
                        axios.post(this.postOrder,  this.fileds).then(response => {
                            if(response.data.status == 200) {
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
                                toastr.success(response.data.message)
                                window.location.href = '{!! Route('order.index') !!}';
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
                                toastr.error(response.data.message)
                            }
                            // window.location.href = '{!! Route('vendor.index') !!}';
                        }).catch((error) => {

                            if(error.response.data.errors['packageOrder.stock'] != undefined){
                                this.validStartStock = error.response.data.errors['packageOrder.stock'][0];
                            }

                        });
                    }
                    // this.validationBeforSendData()

                },
                filterFields() {
                    this.fileds.forEach((value, index) => {
                        // console.log(value.name.length.toString())
                        // arr.push(value);
                        if(value.date_from == ''){
                            this.prosscing = true
                            value.validTimeFrom =true
                        }
                        /*if(value.time_from == ''){
                            this.prosscing = true
                            value.validTimeFrom =true
                        }*/
                        if(value.time_from > value.time_to){
                            this.prosscing = true
                            this.fileds[index].validTimeFroms =true
                        }
                        if(value.date_to == ''){
                            this.prosscing = true
                            value.validTimeTo =true
                        }
                        if(value.time_from == ''){
                            this.prosscing = true
                            value.validTimeFromsf = true   
                        }
                        if(value.time_to == ''){
                            this.prosscing = true
                            value.timeToRange = true   
                        }
                        if(value.type_order == '') {
                            this.prosscing = true
                            value.validTypeOrder = true
                            // this.fileds.splice(index, 1)

                        }
                        //
                        if(value.home == '') {
                            // this.fileds.splice(index, 1)
                            this.prosscing = true
                            value.validHome = true
                        }
                        if(value.sabil == '') {
                            // this.fileds.splice(index, 1)
                            this.prosscing = true
                            value.validSabil = true
                        }
                        if(value.street == '') {
                            // this.fileds.splice(index, 1)
                            this.prosscing = true
                            value.validStreet = true
                        }
                        if(value.block == '') {
                            // this.fileds.splice(index, 1)
                            this.prosscing = true
                            value.validBlock = true
                        }
                        //
                        if(value.latFrom == '') {
                            // this.fileds.splice(index, 1)
                            this.prosscing = true
                            value.validFrom = true
                        }
                        if(value.longFrom == '') {
                            this.prosscing = true
                            value.validFrom = true
                        }
                        if(value.cityFrom == '') {
                            this.prosscing = true
                            value.validFrom = true
                        }
                        if(value.latTo == '') {
                            this.prosscing = true
                            value.validTo = true
                        }
                        if(value.longTo == '') {
                            this.prosscing = true
                            value.validTo = true
                        }
                        if(value.cityTo == '') {
                            this.prosscing = true
                            value.validTo = true
                        }
                        if(this.fileds[index].name.length.toString() == 0) {
                            this.prosscing = true
                            value.validName = true
                        }
                        if(value.mobile == '') {
                            this.prosscing = true
                            // this.fileds.splice(index, 1)
                            value.validMobile = true
                        }
                        // console.log(index);order
                        // if(value.)
                    });
                },
                clearForm() {
                    this.fileds = [{'drivers': '',
                        'start_shift' : '',
                        'end_shift': '',
                        'option': []
                    }]
                },
                getIndex(index){
                    $('#to_address_model').val(index)
                    $('#from_address_model').val(index)
                    $('#exampleModalLabelfromHidden' + index).val('exampleModalLabelfromHidden' + index)
                    $('#exampleModalLabeltoHidden' + index).val('exampleModalLabeltof' + index)
                },
                // change and append lat and long by index id
                onChangeName(index) {
                    if(this.fileds[index].name.length.toString() == 0) {
                        return this.fileds[index].validName = true
                    }
                    this.prosscing = false
                    this.fileds[index].validName = false
                    // From Validation
                    if($('#latHiddenFrom' + index).val() == '') {
                        this.fileds[index].validTo = false
                        return this.fileds[index].validFrom = true
                    }
                    if($('#longHiddenFrom' + index).val() == '') {
                        this.fileds[index].validTo = false
                        return this.fileds[index].validFrom = true
                    }
                    if($('#exampleModalLabelfromf' + index).text() == '') {
                        this.fileds[index].validTo = false
                        return this.fileds[index].validFrom = true
                    }

                    // To Validation
                    if($('#latHiddenTo' + index).val() == '') {
                        this.fileds[index].validFrom = false
                        return this.fileds[index].validTo = true
                    }
                    if($('#longHiddenTo' + index).val() == '') {
                        this.fileds[index].validFrom = false
                        return this.fileds[index].validTo = true
                    }
                    if($('#exampleModalLabeltof' + index).text() == '') {
                        this.fileds[index].validFrom = false
                        return this.fileds[index].validTo = true
                    }
                    this.fileds[index].validFrom = false
                    this.fileds[index].validTo = false
                    //from
                    this.fileds[index].latFrom = $('#latHiddenFrom' + index).val()
                    this.fileds[index].longFrom = $('#longHiddenFrom' + index).val()
                    this.fileds[index].cityFrom = $('#exampleModalLabelfromf' + index).text()
                    //to
                    this.fileds[index].latTo = $('#latHiddenTo' + index).val()
                    this.fileds[index].longTo = $('#longHiddenTo' + index).val()
                    this.fileds[index].cityTo = $('#exampleModalLabeltof' + index).text()
                },
                onChangePhone(index) {
                    this.fileds[index].validMobile = false
                    // console.log(this.fileds[index].mobile.toString().length)
                    if(this.fileds[index].mobile.toString().length == 0) {
                        return this.fileds[index].validMobile = true
                    }
                    this.prosscing = false
                    if(this.fileds[index].mobile.toString().length < 8) {
                        return this.fileds[index].validMobileMin = true
                    }
                    if(this.fileds[index].mobile.toString().length > 10) {
                        return this.fileds[index].validMobileMax = true
                    }
                    this.fileds[index].validMobile = false
                    this.fileds[index].validMobileMin = false
                    this.fileds[index].validMobileMax = false
                    // From Validation
                    if($('#latHiddenFrom' + index).val() == '') {
                        this.fileds[index].validTo = false
                        return this.fileds[index].validFrom = true
                    }
                    if($('#longHiddenFrom' + index).val() == '') {
                        this.fileds[index].validTo = false
                        return this.fileds[index].validFrom = true
                    }
                    if($('#exampleModalLabelfromf' + index).text() == '') {
                        this.fileds[index].validTo = false
                        return this.fileds[index].validFrom = true
                    }

                    // To Validation
                    if($('#latHiddenTo' + index).val() == '') {
                        this.fileds[index].validFrom = false
                        return this.fileds[index].validTo = true
                    }
                    if($('#longHiddenTo' + index).val() == '') {
                        this.fileds[index].validFrom = false
                        return this.fileds[index].validTo = true
                    }
                    if($('#exampleModalLabeltof' + index).text() == '') {
                        this.fileds[index].validFrom = false
                        return this.fileds[index].validTo = true
                    }
                    this.fileds[index].validFrom = false
                    this.fileds[index].validTo = false
                    //from
                    this.fileds[index].latFrom = $('#latHiddenFrom' + index).val()
                    this.fileds[index].longFrom = $('#longHiddenFrom' + index).val()
                    this.fileds[index].cityFrom = $('#exampleModalLabelfromf' + index).text()
                    //to
                    this.fileds[index].latTo = $('#latHiddenTo' + index).val()
                    this.fileds[index].longTo = $('#longHiddenTo' + index).val()
                    this.fileds[index].cityTo = $('#exampleModalLabeltof' + index).text()
                },
                onChangeDate(index) {
                    if(this.fileds[index].date.length.toString() == 0) {
                        return this.fileds[index].validDate = true
                    }
                    this.prosscing = false
                    this.fileds[index].validDate = false
                    // From Validation
                    if($('#latHiddenFrom' + index).val() == '') {
                        this.fileds[index].validTo = false
                        return this.fileds[index].validFrom = true
                    }
                    if($('#longHiddenFrom' + index).val() == '') {
                        this.fileds[index].validTo = false
                        return this.fileds[index].validFrom = true
                    }
                    if($('#exampleModalLabelfromf' + index).text() == '') {
                        this.fileds[index].validTo = false
                        return this.fileds[index].validFrom = true
                    }

                    // To Validation
                    if($('#latHiddenTo' + index).val() == '') {
                        this.fileds[index].validFrom = false
                        return this.fileds[index].validTo = true
                    }
                    if($('#longHiddenTo' + index).val() == '') {
                        this.fileds[index].validFrom = false
                        return this.fileds[index].validTo = true
                    }
                    if($('#exampleModalLabeltof' + index).text() == '') {
                        this.fileds[index].validFrom = false
                        return this.fileds[index].validTo = true
                    }
                    this.fileds[index].validFrom = false
                    this.fileds[index].validTo = false
                    //from
                    this.fileds[index].latFrom = $('#latHiddenFrom' + index).val()
                    this.fileds[index].longFrom = $('#longHiddenFrom' + index).val()
                    this.fileds[index].cityFrom = $('#exampleModalLabelfromf' + index).text()
                    //to
                    this.fileds[index].latTo = $('#latHiddenTo' + index).val()
                    this.fileds[index].longTo = $('#longHiddenTo' + index).val()
                    this.fileds[index].cityTo = $('#exampleModalLabeltof' + index).text()
                },
                onChangeDescription(index) {
                    // From Validation
                    if($('#latHiddenFrom' + index).val() == '') {
                        this.fileds[index].validTo = false
                        return this.fileds[index].validFrom = true
                    }
                    if($('#longHiddenFrom' + index).val() == '') {
                        this.fileds[index].validTo = false
                        return this.fileds[index].validFrom = true
                    }
                    if($('#exampleModalLabelfromf' + index).text() == '') {
                        this.fileds[index].validTo = false
                        return this.fileds[index].validFrom = true
                    }

                    // To Validation
                    if($('#latHiddenTo' + index).val() == '') {
                        this.fileds[index].validFrom = false
                        return this.fileds[index].validTo = true
                    }
                    if($('#longHiddenTo' + index).val() == '') {
                        this.fileds[index].validFrom = false
                        return this.fileds[index].validTo = true
                    }
                    if($('#exampleModalLabeltof' + index).text() == '') {
                        this.fileds[index].validFrom = false
                        return this.fileds[index].validTo = true
                    }
                    this.fileds[index].validFrom = false
                    this.fileds[index].validTo = false
                    //from
                    this.fileds[index].latFrom = $('#latHiddenFrom' + index).val()
                    this.fileds[index].longFrom = $('#longHiddenFrom' + index).val()
                    this.fileds[index].cityFrom = $('#exampleModalLabelfromf' + index).text()
                    //to
                    this.fileds[index].latTo = $('#latHiddenTo' + index).val()
                    this.fileds[index].longTo = $('#longHiddenTo' + index).val()
                    this.fileds[index].cityTo = $('#exampleModalLabeltof' + index).text()
                },
                onChangeDriver(event, index) {
                    console.log(event.target.value)
                    // From Validation
                    if($('#latHiddenFrom' + index).val() == '') {
                        this.fileds[index].validTo = false
                        return this.fileds[index].validFrom = true
                    }
                    if($('#longHiddenFrom' + index).val() == '') {
                        this.fileds[index].validTo = false
                        return this.fileds[index].validFrom = true
                    }
                    if($('#exampleModalLabelfromf' + index).text() == '') {
                        this.fileds[index].validTo = false
                        return this.fileds[index].validFrom = true
                    }

                    // To Validation
                    if($('#latHiddenTo' + index).val() == '') {
                        this.fileds[index].validFrom = false
                        return this.fileds[index].validTo = true
                    }
                    if($('#longHiddenTo' + index).val() == '') {
                        this.fileds[index].validFrom = false
                        return this.fileds[index].validTo = true
                    }
                    if($('#exampleModalLabeltof' + index).text() == '') {
                        this.fileds[index].validFrom = false
                        return this.fileds[index].validTo = true
                    }
                    this.fileds[index].validFrom = false
                    this.fileds[index].validTo = false
                    //from
                    this.fileds[index].latFrom = $('#latHiddenFrom' + index).val()
                    this.fileds[index].longFrom = $('#longHiddenFrom' + index).val()
                    this.fileds[index].cityFrom = $('#exampleModalLabelfromf' + index).text()
                    //to
                    this.fileds[index].latTo = $('#latHiddenTo' + index).val()
                    this.fileds[index].longTo = $('#longHiddenTo' + index).val()
                    this.fileds[index].cityTo = $('#exampleModalLabeltof' + index).text()
                    this.fileds[index].driver = event.target.value
                },
                getVendorFunction() {
                    axios.get(this.getVendor)
                        .then(response => this.vendor_id = response.data.data)
                },
                onElevatorUse(event) {
                    console.log(event.target.value)
                    // this.form.i_elevator_use = event.target.value
                },
                onSelectPackage(event) {
                    let self = this
                    if(event.target.value == 1) {
                        self.resetRepeter()
                        self.order_peer_hours = true
                        self.order_peer_order = false
                    } else if(event.target.value == 0){
                        self.resetRepeter()
                        self.order_peer_order = true
                        self.order_peer_hours = false
                    } else {
                        self.resetRepeter()
                        self.order_peer_hours = false
                        self.order_peer_order = false
                    }
                    // this.form.i_elevator_use = event.target.value
                },
                onChangeDateTo(index) {
                this.fileds[index].validTimeTo = false
                // console.log(this.fileds[index].mobile.toString().length)
                if(this.fileds[index].date_to == '') {
                  return this.fileds[index].validTimeTo = true
                }
                this.prosscing = false
                this.fileds[index].validTimeTo = false
                // From Validation
                if($('#latHiddenFrom' + index).val() == '') {
                    this.fileds[index].validTo = false
                    return this.fileds[index].validFrom = true
                }
                if($('#longHiddenFrom' + index).val() == '') {
                    this.fileds[index].validTo = false
                    return this.fileds[index].validFrom = true
                }
                if($('#exampleModalLabelfromf' + index).text() == '') {
                    this.fileds[index].validTo = false
                    return this.fileds[index].validFrom = true
                }

                // To Validation
                if($('#latHiddenTo' + index).val() == '') {
                    this.fileds[index].validFrom = false
                    return this.fileds[index].validTo = true
                }
                if($('#longHiddenTo' + index).val() == '') {
                    this.fileds[index].validFrom = false
                    return this.fileds[index].validTo = true
                }
                if($('#exampleModalLabeltof' + index).text() == '') {
                    this.fileds[index].validFrom = false
                    return this.fileds[index].validTo = true
                }
                this.fileds[index].validFrom = false
                this.fileds[index].validTo = false
                //from
                this.fileds[index].latFrom = $('#latHiddenFrom' + index).val()
                this.fileds[index].longFrom = $('#longHiddenFrom' + index).val()
                this.fileds[index].cityFrom = $('#exampleModalLabelfromf' + index).text()
                //to
                this.fileds[index].latTo = $('#latHiddenTo' + index).val()
                this.fileds[index].longTo = $('#longHiddenTo' + index).val()
                this.fileds[index].cityTo = $('#exampleModalLabeltof' + index).text()
            },
                onChangeTimeTo(index) {
                    if(this.fileds[index].time_from > this.fileds[index].time_to){
                            // console.log('a')
                                this.prosscing = true
                                this.fileds[index].validTimeFroms =true
                                return
                            }
                            this.fileds[index].validTimeFroms =false
                            this.prosscing = false
                    this.fileds[index].validTimeTo = false
                    // console.log(this.fileds[index].mobile.toString().length)
                    if(this.fileds[index].time_to == '') {
                      return this.fileds[index].validTimeTo = true
                    }
                    this.prosscing = false
                    this.fileds[index].validTimeTo = false
                    // From Validation
                    if($('#latHiddenFrom' + index).val() == '') {
                        this.fileds[index].validTo = false
                        return this.fileds[index].validFrom = true
                    }
                    if($('#longHiddenFrom' + index).val() == '') {
                        this.fileds[index].validTo = false
                        return this.fileds[index].validFrom = true
                    }
                    if($('#exampleModalLabelfromf' + index).text() == '') {
                        this.fileds[index].validTo = false
                        return this.fileds[index].validFrom = true
                    }
    
                    // To Validation
                    if($('#latHiddenTo' + index).val() == '') {
                        this.fileds[index].validFrom = false
                        return this.fileds[index].validTo = true
                    }
                    if($('#longHiddenTo' + index).val() == '') {
                        this.fileds[index].validFrom = false
                        return this.fileds[index].validTo = true
                    }
                    if($('#exampleModalLabeltof' + index).text() == '') {
                        this.fileds[index].validFrom = false
                        return this.fileds[index].validTo = true
                    }
                    this.fileds[index].validFrom = false
                    this.fileds[index].validTo = false
                    //from
                    this.fileds[index].latFrom = $('#latHiddenFrom' + index).val()
                    this.fileds[index].longFrom = $('#longHiddenFrom' + index).val()
                    this.fileds[index].cityFrom = $('#exampleModalLabelfromf' + index).text()
                    //to
                    this.fileds[index].latTo = $('#latHiddenTo' + index).val()
                    this.fileds[index].longTo = $('#longHiddenTo' + index).val()
                    this.fileds[index].cityTo = $('#exampleModalLabeltof' + index).text()
                },
                onChangeDateFrom(index) {
                    this.fileds[index].validTimeFrom = false
                    // console.log(this.fileds[index].mobile.toString().length)
                    if(this.fileds[index].date_from == '') {
                      return this.fileds[index].validTimeFrom = true
                    }
                    this.prosscing = false
                    this.fileds[index].validTimeFrom = false
                    // From Validation
                    if($('#latHiddenFrom' + index).val() == '') {
                        this.fileds[index].validTo = false
                        return this.fileds[index].validFrom = true
                    }
                    if($('#longHiddenFrom' + index).val() == '') {
                        this.fileds[index].validTo = false
                        return this.fileds[index].validFrom = true
                    }
                    if($('#exampleModalLabelfromf' + index).text() == '') {
                        this.fileds[index].validTo = false
                        return this.fileds[index].validFrom = true
                    }
    
                    // To Validation
                    if($('#latHiddenTo' + index).val() == '') {
                        this.fileds[index].validFrom = false
                        return this.fileds[index].validTo = true
                    }
                    if($('#longHiddenTo' + index).val() == '') {
                        this.fileds[index].validFrom = false
                        return this.fileds[index].validTo = true
                    }
                    if($('#exampleModalLabeltof' + index).text() == '') {
                        this.fileds[index].validFrom = false
                        return this.fileds[index].validTo = true
                    }
                    this.fileds[index].validFrom = false
                    this.fileds[index].validTo = false
                    //from
                    this.fileds[index].latFrom = $('#latHiddenFrom' + index).val()
                    this.fileds[index].longFrom = $('#longHiddenFrom' + index).val()
                    this.fileds[index].cityFrom = $('#exampleModalLabelfromf' + index).text()
                    //to
                    this.fileds[index].latTo = $('#latHiddenTo' + index).val()
                    this.fileds[index].longTo = $('#longHiddenTo' + index).val()
                    this.fileds[index].cityTo = $('#exampleModalLabeltof' + index).text()
                },
                onChangeTimeFrom(index) {
                    this.fileds[index].validTimeFrom = false
                    // console.log(this.fileds[index].mobile.toString().length)
                    if(this.fileds[index].time_from == '') {
                      return this.fileds[index].validTimeFrom = true
                    }
                    this.prosscing = false
                    this.fileds[index].validTimeFrom = false
                    // From Validation
                    if($('#latHiddenFrom' + index).val() == '') {
                        this.fileds[index].validTo = false
                        return this.fileds[index].validFrom = true
                    }
                    if($('#longHiddenFrom' + index).val() == '') {
                        this.fileds[index].validTo = false
                        return this.fileds[index].validFrom = true
                    }
                    if($('#exampleModalLabelfromf' + index).text() == '') {
                        this.fileds[index].validTo = false
                        return this.fileds[index].validFrom = true
                    }
    
                    // To Validation
                    if($('#latHiddenTo' + index).val() == '') {
                        this.fileds[index].validFrom = false
                        return this.fileds[index].validTo = true
                    }
                    if($('#longHiddenTo' + index).val() == '') {
                        this.fileds[index].validFrom = false
                        return this.fileds[index].validTo = true
                    }
                    if($('#exampleModalLabeltof' + index).text() == '') {
                        this.fileds[index].validFrom = false
                        return this.fileds[index].validTo = true
                    }
                    this.fileds[index].validFrom = false
                    this.fileds[index].validTo = false
                    //from
                    this.fileds[index].latFrom = $('#latHiddenFrom' + index).val()
                    this.fileds[index].longFrom = $('#longHiddenFrom' + index).val()
                    this.fileds[index].cityFrom = $('#exampleModalLabelfromf' + index).text()
                    //to
                    this.fileds[index].latTo = $('#latHiddenTo' + index).val()
                    this.fileds[index].longTo = $('#longHiddenTo' + index).val()
                    this.fileds[index].cityTo = $('#exampleModalLabeltof' + index).text()
                },
            },
        }
        Vue.createApp(AttributeBinding).mount('#app')




        var initMap = function () {


            var map1 = function() {
                var mapOptions, map, marker, searchBox, city,
                    infoWindow = '';
                @if(auth()->user()->store()->exists())
                $("#lat_from").val('{!! auth()->user()->store->lat !!}');
                $("#long_from").val('{!! auth()->user()->store->long !!}');
                var latEl = $("#lat_from").val('{!! auth()->user()->store->lat !!}');
                var longEl = $("#long_from").val('{!! auth()->user()->store->long !!}');
                @else
                $("#lat_from").val("");
                $("#long_from").val("");
                var latEl = document.querySelector( '#lat_from' );
                var longEl = document.querySelector( '#long_from' );
                @endif
                // init location

                var addressEl = document.querySelector( '#map-search' );

                var element = document.getElementById( 'map' );
                var city = document.querySelector( '.reg-input-city' );
                mapOptions = {
                    // How far the maps zooms in.
                    zoom: 8,
                    // Current Lat and Long position of the pin/
                    @if(auth()->user()->store()->exists())
                    center: new google.maps.LatLng( {!! auth()->user()->store->lat !!}, {!! auth()->user()->store->long !!} ),
                    @else
                    center: new google.maps.LatLng( 29.33744230597737, 48.022533337402336 ),
                    @endif

                    disableDefaultUI: false, // Disables the controls like zoom control on the map if set to true
                    scrollWheel: true, // If set to false disables the scrolling on the map.
                    draggable: true, // If set to false , you cannot move the map around.
                    // mapTypeId: google.maps.MapTypeId.HYBRID, // If set to HYBRID its between sat and ROADMAP, Can be set to SATELLITE as well.
                    // maxZoom: 11, // Wont allow you to zoom more than this
                    // minZoom: 9  // Wont allow you to go more up.

                };

                /**
                 * Creates the map using google function google.maps.Map() by passing the id of canvas and
                 * mapOptions object that we just created above as its parameters.
                 *
                 */
                // Create an object map with the constructor function Map()
                map = new google.maps.Map( element, mapOptions ); // Till this like of code it loads up the map.

                /**
                 * Creates the marker on the map
                 *
                 */
                marker = new google.maps.Marker({
                    position: mapOptions.center,
                    map: map,
                    // icon: 'http://pngimages.net/sites/default/files/google-maps-png-image-70164.png',
                    draggable: true
                });

                const triangleCoords = [
                    { lat: 25.774, lng: -80.19 },
                    { lat: 18.466, lng: -66.118 },
                    { lat: 32.321, lng: -64.757 },
                    { lat: 25.774, lng: -80.19 },
                ];
                // Construct the polygon, including both paths.
                const bermudaTriangle = new google.maps.Polygon({
                    paths: triangleCoords,
                    strokeColor: "#FFC107",
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: "#FFC107",
                    fillOpacity: 0.35,
                });
                marker = new google.maps.Marker({
                    position: mapOptions.center,
                    map: map,
                    // icon: 'http://pngimages.net/sites/default/files/google-maps-png-image-70164.png',
                    draggable: true
                });
                bermudaTriangle.setMap(map, marker);
                /**
                 * Creates a search box
                 */
                searchBox = new google.maps.places.SearchBox( addressEl );

                /**
                 * When the place is changed on search box, it takes the marker to the searched location.
                 */
                google.maps.event.addListener( searchBox, 'places_changed', function () {
                    let indexId = $('#from_address_model').val()
                    var places = searchBox.getPlaces(),
                        bounds = new google.maps.LatLngBounds(),
                        i, place, lat, long, resultArray,
                        addresss = places[0].formatted_address;
                    $('#exampleModalLabelfromf').text(addresss);
                    $('#exampleModalLabelfromHidden').val(addresss);
                    $('#exampleModalLabelfromf' + indexId).text(addresss);


                    for( i = 0; place = places[i]; i++ ) {
                        bounds.extend( place.geometry.location );
                        marker.setPosition( place.geometry.location );  // Set marker position new.
                    }

                    map.fitBounds( bounds );  // Fit to the bound
                    map.setZoom( 15 ); // This function sets the zoom to 15, meaning zooms to level 15.
                    // console.log( map.getZoom() );

                    lat = marker.getPosition().lat();
                    long = marker.getPosition().lng();
                    $('#latHiddenFrom' + indexId).val(lat)
                    $('#longHiddenFrom' + indexId).val(long)
                    latEl.value = lat;
                    longEl.value = long;

                    resultArray =  places[0].address_components;

                    // Get the city and set the city input value to the one selected
                    for( var i = 0; i < resultArray.length; i++ ) {
                        if ( resultArray[ i ].types[0] && 'administrative_area_level_2' === resultArray[ i ].types[0] ) {
                            citi = resultArray[ i ].long_name;

                            // console.log(city)
                            // city.value = citi;
                        }
                    }

                    // Closes the previous info window if it already exists
                    if ( infoWindow ) {
                        infoWindow.close();
                    }
                    /**
                     * Creates the info Window at the top of the marker
                     */
                    infoWindow = new google.maps.InfoWindow({
                        content: addresss
                    });

                    infoWindow.open( map, marker );
                } );


                /**
                 * Finds the new position of the marker when the marker is dragged.
                 */
                google.maps.event.addListener( marker, "dragend", function ( event ) {
                    var lat, long, address, resultArray, citi;
                    let indexIdMarker = $('#from_address_model').val()
                    lat = marker.getPosition().lat();
                    long = marker.getPosition().lng();
                    $('#latHiddenFrom' + indexIdMarker).val(lat)
                    $('#longHiddenFrom' + indexIdMarker).val(long)
                    var geocoder = new google.maps.Geocoder();
                    geocoder.geocode( { latLng: marker.getPosition() }, function ( result, status ) {
                        if ( 'OK' === status ) {  // This line can also be written like if ( status == google.maps.GeocoderStatus.OK ) {
                            address = result[0].formatted_address;
                            resultArray =  result[0].address_components;
                            $('#exampleModalLabelfromf').text(address);
                            $('#exampleModalLabelfromHidden').val(address);
                            $('#exampleModalLabelfromf' + indexIdMarker).text(address);
                            // Get the city and set the city input value to the one selected
                            for( var i = 0; i < resultArray.length; i++ ) {
                                if ( resultArray[ i ].types[0] && 'administrative_area_level_2' === resultArray[ i ].types[0] ) {
                                    citi = resultArray[ i ].long_name;
                                    console.log( citi );
                                }
                            }
                            addressEl.value = address;
                            latEl.value = lat;
                            longEl.value = long;
                            console.log(lat)
                        } else {
                            console.log( 'Geocode was not successful for the following reason: ' + status );
                        }

                        // Closes the previous info window if it already exists
                        if ( infoWindow ) {
                            infoWindow.close();
                        }

                        /**
                         * Creates the info Window at the top of the marker
                         */
                        infoWindow = new google.maps.InfoWindow({
                            content: address
                        });

                        infoWindow.open( map, marker );
                    } );
                });
            }

            var map2 = function() {
                var mapOptions, map, marker, searchBox, city,
                    infoWindow = '';

                $("#lat_to").val("");
                $("#long_to").val("");
                var latEl = document.querySelector( '#lat_to' );
                var longEl = document.querySelector( '#long_to' );
                // init location

                var addressEl = document.querySelector( '#maps-search' );

                var element = document.getElementById( 'maps' );
                var city = document.querySelector( '.reg-input-city' );

                mapOptions = {
                    // How far the maps zooms in.
                    zoom: 8,
                    // Current Lat and Long position of the pin/
                    center: new google.maps.LatLng( 29.33744230597737, 48.022533337402336 ),

                    disableDefaultUI: false, // Disables the controls like zoom control on the map if set to true
                    scrollWheel: true, // If set to false disables the scrolling on the map.
                    draggable: true, // If set to false , you cannot move the map around.
                    // mapTypeId: google.maps.MapTypeId.HYBRID, // If set to HYBRID its between sat and ROADMAP, Can be set to SATELLITE as well.
                    // maxZoom: 11, // Wont allow you to zoom more than this
                    // minZoom: 9  // Wont allow you to go more up.

                };

                /**
                 * Creates the map using google function google.maps.Map() by passing the id of canvas and
                 * mapOptions object that we just created above as its parameters.
                 *
                 */
                // Create an object map with the constructor function Map()
                map = new google.maps.Map( element, mapOptions ); // Till this like of code it loads up the map.

                /**
                 * Creates the marker on the map
                 *
                 */
                marker = new google.maps.Marker({
                    position: mapOptions.center,
                    map: map,
                    // icon: 'http://pngimages.net/sites/default/files/google-maps-png-image-70164.png',
                    draggable: true
                });

                /**
                 * Creates a search box
                 */
                searchBox = new google.maps.places.SearchBox( addressEl );

                const triangleCoords = [
                    { lat: 25.774, lng: -80.19 },
                    { lat: 18.466, lng: -66.118 },
                    { lat: 32.321, lng: -64.757 },
                    { lat: 25.774, lng: -80.19 },
                ];
                // Construct the polygon, including both paths.
                const bermudaTriangle = new google.maps.Polygon({
                    paths: triangleCoords,
                    strokeColor: "#FFC107",
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: "#FFC107",
                    fillOpacity: 0.35,
                });
                marker = new google.maps.Marker({
                    position: mapOptions.center,
                    map: map,
                    // icon: 'http://pngimages.net/sites/default/files/google-maps-png-image-70164.png',
                    draggable: true
                });
                bermudaTriangle.setMap(map, marker);
                /**
                 * When the place is changed on search box, it takes the marker to the searched location.
                 */
                google.maps.event.addListener( searchBox, 'places_changed', function () {
                    var places = searchBox.getPlaces(),
                        bounds = new google.maps.LatLngBounds(),
                        i, place, lat, long, resultArray,
                        addresss = places[0].formatted_address;
                    let indexId = $('#to_address_model').val()
                    $('#exampleModalLabeltof').text(addresss);
                    $('#exampleModalLabeltoHidden').val(addresss);
                    $('#exampleModalLabeltof' + indexId).text(addresss);
                    for( i = 0; place = places[i]; i++ ) {
                        bounds.extend( place.geometry.location );
                        marker.setPosition( place.geometry.location );  // Set marker position new.
                    }

                    map.fitBounds( bounds );  // Fit to the bound
                    map.setZoom( 15 ); // This function sets the zoom to 15, meaning zooms to level 15.
                    // console.log( map.getZoom() );

                    lat = marker.getPosition().lat();
                    long = marker.getPosition().lng();
                    $('#latHiddenTo' + indexId).val(lat)
                    $('#longHiddenTo' + indexId).val(long)
                    latEl.value = lat;
                    longEl.value = long;

                    resultArray =  places[0].address_components;

                    // Get the city and set the city input value to the one selected
                    for( var i = 0; i < resultArray.length; i++ ) {
                        if ( resultArray[ i ].types[0] && 'administrative_area_level_2' === resultArray[ i ].types[0] ) {
                            citi = resultArray[ i ].long_name;

                            // console.log(city)
                            // city.value = citi;
                        }
                    }

                    // Closes the previous info window if it already exists
                    if ( infoWindow ) {
                        infoWindow.close();
                    }
                    /**
                     * Creates the info Window at the top of the marker
                     */
                    infoWindow = new google.maps.InfoWindow({
                        content: addresss
                    });

                    infoWindow.open( map, marker );
                } );


                /**
                 * Finds the new position of the marker when the marker is dragged.
                 */
                google.maps.event.addListener( marker, "dragend", function ( event ) {
                    var lat, long, address, resultArray, citi;
                    let indexIdMarker = $('#from_address_model').val()
                    lat = marker.getPosition().lat();
                    long = marker.getPosition().lng();
                    $('#latHiddenTo' + indexIdMarker).val(lat)
                    $('#longHiddenTo' + indexIdMarker).val(long)

                    var geocoder = new google.maps.Geocoder();
                    geocoder.geocode( { latLng: marker.getPosition() }, function ( result, status ) {
                        if ( 'OK' === status ) {  // This line can also be written like if ( status == google.maps.GeocoderStatus.OK ) {
                            address = result[0].formatted_address;
                            resultArray =  result[0].address_components;
                            $('#exampleModalLabeltof').text(address);
                            $('#exampleModalLabeltoHidden').val(address);

                            // Get the city and set the city input value to the one selected
                            for( var i = 0; i < resultArray.length; i++ ) {
                                if ( resultArray[ i ].types[0] && 'administrative_area_level_2' === resultArray[ i ].types[0] ) {
                                    citi = resultArray[ i ].long_name;
                                    console.log( citi );
                                }
                            }
                            addressEl.value = address;
                            $('#exampleModalLabeltof' + indexIdMarker).text(address);
                            latEl.value = lat;
                            longEl.value = long;
                            console.log(lat)
                        } else {
                            console.log( 'Geocode was not successful for the following reason: ' + status );
                        }

                        // Closes the previous info window if it already exists
                        if ( infoWindow ) {
                            infoWindow.close();
                        }

                        /**
                         * Creates the info Window at the top of the marker
                         */
                        infoWindow = new google.maps.InfoWindow({
                            content: address
                        });

                        infoWindow.open( map, marker );
                    } );
                });
            }

            return {
                // public functions
                init: function() {
                    // default charts
                    map1();
                    map2();
                }
            };
        }();
        jQuery(document).ready(function() {
            initMap.init();
        });
        @if(!auth()->user()->hasRole('superadministrator'))
        $(document).ready(function(){ //type_order,package
            $("select#package").change(function(){
                var selectedCountry = $(this).children("option:selected").val();
                console.log(selectedCountry)
                if(selectedCountry === '1') {
                    // $("#lat_from").prop('disabled', false);
                    // $("#long_from").prop('disabled', false);
                    $('#exampleModalLabelfrom').text('');
                    $('#exampleModalLabelfromf').text('');
                } else {
                    // $("#lat_from").prop('disabled', true);
                    // $("#long_from").prop('disabled', true);
                    let lato = '{!! auth()->user()->store->lat !!}';
                    let longo = '{!! auth()->user()->store->long !!}';
                    $("#lat_from").val('{!! auth()->user()->store->lat !!}');
                    $("#long_from").val('{!! auth()->user()->store->long !!}');
                    console.log(1)
                    let googleApi = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='+lato+','+longo+'&key=AIzaSyDQDC3VV5CGRaueUYpEEJ308KNx8zbG5t0';
                    $.get(googleApi, function(data, status){
                        // console.log("Data: " + data['results'][0]['formatted_address'] + "\nStatus: " + status);
                        $('#exampleModalLabelfrom').text(data['results'][0]['formatted_address']);
                        $('#exampleModalLabelfromf').text(data['results'][0]['formatted_address']);
                    });
                }
            });
        });
        @endrole

        $(function() {
            $("#kt_form_1").validate({
                rules: {
                    phone: {
                        required: true,
                        number: true,
                        minlength: 8,
                        maxlength: 10,
                        // min:8,
                        // max:10,
                    },
                },
                messages: {
                    phone: {
                        required: '{{__('store.phoneRequires')}}',
                        number: '{{__('store.phoneNumber')}}',
                        minlength: '{{ __('auth.passwordMinEight') }}',
                        {{--min: '{{ __('auth.passwordMinEight') }}',--}}
                        maxlength: '{{ __('auth.passwordMaxEight') }}',
                        {{--max: '{{ __('auth.passwordMaxEight') }}',--}}
                    },
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
@endpush
@section('pageTitle')
    <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
    <span class="text-muted font-weight-bold mr-4">@lang('navbar.createOrder')</span>
    {{--<a href="#" class="btn btn-light-warning font-weight-bolder btn-sm">Add New</a>--}}
@endsection
@section('content')
    <div class="row ">
        <div class="col-md-12">
            <h3 class="card-title">
                <div class="form-group row align-items-center">
                    <button v-show="disableAddOrder" type="button" @click="addRows" class="btn btn-success mr-2 btn-sm"><i class="fas fa-plus"></i></button>
                    <button :disabled="prosscing == true" type="button" @click="sendData" class="btn btn-primary mr-2">@lang('form.create')</button>
                    <span v-if="prosscing" class="spinner spinner-dark spinner-right"></span>
                    <button type="button" @click="resetRepeter" class="btn btn-secondary mr-2">@lang('form.delete')</button>

                </div>
            </h3>

        </div>
    </div>

    <div class="row" v-for="(vaule, index) in fileds" :key="index">
        <div class="col-md-12">
            <!--begin::Card-->
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header">
                    <h3 class="card-title">
                        <span class="fas fa-shopping-cart icon-lg"></span> ( @{{index + 1}} )
                    </h3>
                    <h3 class="card-title">
                        <button type="button" class="btn btn-danger btn-sm" @click="removeRows(index)"><i class="far fa-trash-alt"></i></button>
                    </h3>
                </div>
                <!--begin::Form-->
                <form id="kt_form_1" method="post" action="{{Route('order.store')}}">
                    @csrf

                    <input :id="'exampleModalLabelfromHidden'+index" type="hidden" name="from_address">
                    <input :id="'exampleModalLabeltoHidden'+index" type="hidden" name="to_address">

                    <input :id="'latHiddenFrom'+index" type="hidden" name="to_address">
                    <input :id="'longHiddenFrom'+index" type="hidden" name="to_address">

                    <input :id="'latHiddenTo'+index" type="hidden" name="to_address">
                    <input :id="'longHiddenTo'+index" type="hidden" name="to_address">
                    <div class="card-body">
                        <div class="form-group row">

                            <div class="col-lg-2">
                                <label for="exampleSelect1">@lang('order.typeOfOrder')
                                    <span class="text-danger">*</span></label>
                                <select @change="onChangeTypeOrder($event, index)" name="type_order" class="form-control @error('type_order') is-invalid @enderror" id="package">
                                    <option value="">--</option>
                                    <option value="1">@lang('order.twoWay')</option>
                                    <option value="0">@lang('order.oneWay')</option>
                                </select>

                                <span v-if="vaule.validTypeOrder" class="text-danger">@lang('order.typeOfOrderV')</span>
                            </div>
                            <div class="col-lg-2">
                                <label>@lang('order.from_address')
                                    <span class="text-danger">*</span></label>
                                <div class="input-group ">
                                    <div @click="getIndex(index)" data-toggle="modal" data-target="#from_address_input" class="input-group-prepend">

                                        <span style="color:#a4ac26" :id="'exampleModalLabelfromf'+index"></span>

                                        <span class="input-group-text">

                                                                <span class="svg-icon svg-icon-md"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo1\dist/../src/media/svg/icons\Map\Marker2.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                        <rect x="0" y="0" width="24" height="24"/>
                                                                        <path d="M9.82829464,16.6565893 C7.02541569,15.7427556 5,13.1079084 5,10 C5,6.13400675 8.13400675,3 12,3 C15.8659932,3 19,6.13400675 19,10 C19,13.1079084 16.9745843,15.7427556 14.1717054,16.6565893 L12,21 L9.82829464,16.6565893 Z M12,12 C13.1045695,12 14,11.1045695 14,10 C14,8.8954305 13.1045695,8 12,8 C10.8954305,8 10,8.8954305 10,10 C10,11.1045695 10.8954305,12 12,12 Z" fill="#000000"/>
                                                                    </g>
                                                                </svg><!--end::Svg Icon--></span>
                                                            </span>
                                    </div> &nbsp
                                    <input style="display: none" id="lat_from" autocapitalize="off" autocomplete="off" type="text" name="lat_from" class="form-control @error('lat_from') is-invalid @enderror" placeholder="@lang('order.from_addressDescription')" />
                                    <input style="display: none" id="long_from" autocapitalize="off" autocomplete="off" type="text" name="long_from" class="form-control @error('long_from') is-invalid @enderror" placeholder="@lang('order.from_addressDescription')" />
                                </div>
                                @error('lat_from')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                                @error('long_from')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <span class="text-danger" v-if="vaule.validFrom">@lang('store.latRequires')</span>
                            </div>
                            <div class="col-lg-2 mb-5">
                                <label>@lang('order.to_address')
                                    <span class="text-danger">*</span></label>
                                <div class="input-group ">
                                    <div @click="getIndex(index)" data-toggle="modal" data-target="#to_address_input" class="input-group-prepend">
                                        <span style="color:#a4ac26" :id="'exampleModalLabeltof'+index"></span>

                                        <span class="input-group-text">

                                                                <span class="svg-icon svg-icon-md"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo1\dist/../src/media/svg/icons\Map\Marker2.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                        <rect x="0" y="0" width="24" height="24"/>
                                                                        <path d="M9.82829464,16.6565893 C7.02541569,15.7427556 5,13.1079084 5,10 C5,6.13400675 8.13400675,3 12,3 C15.8659932,3 19,6.13400675 19,10 C19,13.1079084 16.9745843,15.7427556 14.1717054,16.6565893 L12,21 L9.82829464,16.6565893 Z M12,12 C13.1045695,12 14,11.1045695 14,10 C14,8.8954305 13.1045695,8 12,8 C10.8954305,8 10,8.8954305 10,10 C10,11.1045695 10.8954305,12 12,12 Z" fill="#000000"/>
                                                                    </g>
                                                                </svg><!--end::Svg Icon--></span>
                                                            </span>
                                    </div> &nbsp
                                    <input style="display: none" value="{{ old('lat_to') }}" id="lat_to" autocapitalize="off" autocomplete="off" type="text" name="lat_to" class="form-control @error('lat_to') is-invalid @enderror" placeholder="@lang('order.to_addressDescription')" />
                                    <input style="display: none" value="{{ old('long_to') }}" id="long_to" autocapitalize="off" autocomplete="off" type="text" name="long_to" class="form-control @error('long_to') is-invalid @enderror" placeholder="@lang('order.to_addressDescription')" />
                                </div>

                                @error('lat_to')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                                @error('long_to')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <span class="text-danger" v-if="vaule.validTo">@lang('store.longRequires')</span>
                            </div>
                            <div class="col-lg-2">
                                <label>@lang('order.name')
                                    <span class="text-danger">*</span></label>
                                <input v-model="fileds[index].name" v-on:keyup="onChangeName(index)" :disabled="fileds[index].showField" id="pac-input" autocapitalize="off" autocomplete="off" value="{{old('name')}}" type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="@lang('order.name')" />
                                @error('name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <span class="text-danger" v-if="vaule.validName">@lang('order.nameRequired')</span>
                            </div>
                            <div class="col-lg-2">
                                <label>@lang('order.phone')
                                    <span class="text-danger">*</span></label>
                                <input name="phone" v-model="fileds[index].mobile" v-on:keyup="onChangePhone(index)" :disabled="fileds[index].showField" min="1" autocomplete="off" value="{{old('phone')}}" type="number" class="form-control @error('phone') is-invalid @enderror" name="phone" placeholder="@lang('order.phone')" />

                                <span class="text-danger" v-if="vaule.validMobile">@lang('order.mobileRequired')</span>
                            </div>
                            <div class="col-lg-2">
                                <label>@lang('order.home')
                                    <span class="text-danger">*</span></label>
                                <input v-model="fileds[index].home" v-on:keyup="onChangeName(index)" :disabled="fileds[index].showField" autocapitalize="off" autocomplete="off" value="{{old('home')}}" type="text" name="home" class="form-control @error('home') is-invalid @enderror" placeholder="@lang('order.home')" />

                                <span class="text-danger" v-if="vaule.validHome">@lang('order.homeRequired')</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            
                            <div class="col-lg-2">
                                <label>@lang('order.sabil')
                                    <span class="text-danger">*</span></label>
                                <input v-model="fileds[index].sabil" v-on:keyup="onChangePhone(index)" :disabled="fileds[index].showField" autocomplete="off" value="{{old('sabil')}}" type="text" class="form-control @error('sabil') is-invalid @enderror" name="sabil" placeholder="@lang('order.sabil')" />

                                <span class="text-danger" v-if="vaule.validSabil">@lang('order.sabilRequired'),</span>
                            </div>
                            <div class="col-lg-2">
                                <label>@lang('order.street')
                                    <span class="text-danger">*</span></label>
                                <input v-model="fileds[index].street" v-on:keyup="onChangePhone(index)" :disabled="fileds[index].showField" autocomplete="off" value="{{old('street')}}" type="text" class="form-control @error('street') is-invalid @enderror" name="street" placeholder="@lang('order.street')" />

                                <span class="text-danger" v-if="vaule.validStreet">@lang('order.streetRequired'),</span>
                            </div>
                            <div class="col-lg-2">
                                <label>@lang('order.block')
                                    <span class="text-danger">*</span></label>
                                <input v-model="fileds[index].block" v-on:keyup="onChangeBlock(index)" :disabled="fileds[index].showField" autocomplete="off" value="{{old('block')}}" type="text" class="form-control @error('block') is-invalid @enderror" name="block" placeholder="@lang('order.block')" />

                                <span class="text-danger" v-if="vaule.validBlock">@lang('order.blockRequired'),</span>
                            </div>
                            <div class="col-lg-6">
                                <label for="exampleSelect1">@lang('order.description')</label>
                                <textarea v-model="fileds[index].about" v-on:keyup="onChangeDescription(index)" :disabled="fileds[index].showField" autocomplete="off" name="description" class="form-control @error('description') is-invalid @enderror" />{{old('description')}}</textarea>
                                @error('description')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-2 mt-5">
                                <label for="exampleSelect1">@lang('order.date_from')
                                    <span class="text-danger">*</span></label>
                                <div class="input-daterange input-group @error('start') is-invalid @enderror">
                                    <input :disabled="fileds[index].showField" v-on:change="onChangeDateFrom(index)" v-model="fileds[index].date_from" min="{{Carbon\Carbon::now()->format('Y-m-d')}}" autocomplete="off" type="date" class="form-control"/>
                                    
                                </div>
                                <span class="text-danger" v-if="vaule.validTimeFrom">@lang('order.timeFromRequired')</span>

                            </div>
                            <div class="col-lg-2 mt-5">
                                <label for="exampleSelect1">@lang('order.date_from')
                                    <span class="text-danger">*</span></label>
                                <div class="input-daterange input-group @error('start') is-invalid @enderror">
                                    
                                    <input :disabled="fileds[index].showField" v-on:change="onChangeTimeFrom(index)" v-model="fileds[index].time_from" @change="updateValidationFrom(index)" id="from_time" autocomplete="off" type="time" class="form-control"/>
                                </div>
                                <span class="text-danger" v-if="vaule.show_validation_time_from">@lang('order.show_validation_time_from')</span>

                            </div>
                            <div class="col-lg-2 mt-5">
                                <label for="exampleSelect1">@lang('order.date_to')
                                    <span class="text-danger">*</span></label>
                                <div class="input-daterange input-group @error('start') is-invalid @enderror">
                                    <input :disabled="fileds[index].showField" v-on:change="onChangeDateTo(index)" :min="fileds[index].date_from" v-model="fileds[index].date_to" autocomplete="off" type="date" class="form-control"/>
                                    
                                </div>
                                <span class="text-danger" v-if="vaule.validTimeTo">@lang('order.timeToRequired')</span>
                            </div>
                            <div class="col-lg-2 mt-5">
                                <label for="exampleSelect1">@lang('order.date_to')
                                    <span class="text-danger">*</span></label>
                                <div class="input-daterange input-group @error('start') is-invalid @enderror">
                                    
                                    <input :disabled="fileds[index].showField" @change="updateValidationTo(index)" v-on:change="onChangeTimeTo(index)" v-model="fileds[index].time_to" autocomplete="off" type="time" class="form-control"/>
                                </div>
                                <span class="text-danger" v-if="vaule.show_validation_time_to">@lang('order.show_validation_time_to')</span>
                            </div>
                            
                            
                            
                            
                            
                            <div class="col-lg-2 mt-5">
                                <label for="exampleSelect1">@lang('navbar.area')</label>
                                <select @change="onChangeArea($event,index)" name="status" class="form-control @error('status') is-invalid @enderror" id="exampleSelect1">
                                    <option value="">--</option>
                                    <option v-for="option in optionsArea" :value="option.id">@{{option.name['ar']}}</option>
                                </select>
                                <span v-if="validCity" class="text-danger">@{{ validCity }}</span>
                            </div>
                            <div class="col-lg-2 mt-5">
                                <label for="exampleSelect1">@lang('navbar.city')</label>
                                <select v-model="form.city" @change="onChangeCity($event,index)" name="status" class="form-control @error('status') is-invalid @enderror" id="exampleSelect1">
                                    <option value="">--</option>
                                    <option v-for="option in fileds[index].optionsCity" :value="option.id">@{{option.name}}</option>
                                </select>
                                <span v-if="validCity" class="text-danger">@{{ validCity }}</span>
                            </div>
                            <div class="col-lg-2 mt-5">
                                <label>@lang('order.items')</label>
                                <input v-model="fileds[index].items" v-on:keyup="onChangePhone(index)" :disabled="fileds[index].showField" autocomplete="off" value="{{old('street')}}" type="number" class="form-control @error('street') is-invalid @enderror" name="street" placeholder="@lang('order.items')" />

                                
                            </div>
                            <div class="col-lg-2 mt-5">
                                <label>@lang('order.payment_method')</label>
                                <select :disabled="disabeldOption" @change="onChangePaymentType($event, index)" name="type_order" class="form-control @error('type_order') is-invalid @enderror" id="package">
                                    <option value="">--</option>
                                    <option value="1">@lang('order.cash')</option>
                                    <option value="0">@lang('order.online')</option>
                                </select>
                            </div>
                            <div class="col-lg-2 mt-5">
                                <label>@lang('order.totale_amount')</label>
                                <input v-model="fileds[index].totale_amount" v-on:keyup="onChangePhone(index)" :disabled="fileds[index].showField" autocomplete="off" value="{{old('street')}}" type="number" class="form-control @error('street') is-invalid @enderror" name="street" placeholder="@lang('order.totale_amount')" />

                                
                            </div>
                            
                            
                            <div class="col-lg-2 mt-5">
                                <label>@lang('order.Order Reference')</label>
                                <input v-model="fileds[index].order_reference" v-on:keyup="onChangePhone(index)" :disabled="fileds[index].showField" autocomplete="off" value="{{old('order_reference')}}" type="text" class="form-control @error('street') is-invalid @enderror" name="order_reference" placeholder="@lang('order.Order Reference')" />

                                
                            </div>
                            
                            
                            
                            
                        </div>
                    </div>
                    <div class="card-footer">
                    <!--<button type="submit" class="btn btn-primary mr-2">@lang('form.create')</button>-->
                    <!--<a href="{{Route('order.index')}}" class="btn btn-secondary">@lang('form.cancel')</a>-->
                    </div>
                </form>
                <!--end::Form-->
            </div>
            <!--end::Card-->
        </div>
    </div>

    <!-- Modal-->
    <div class="modal fade" id="from_address_input" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabelfrom"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                    <input id="from_address_model" class="form-control controls" type="hidden">
                </div>
                <div class="modal-body">
                    <input
                        id="pac-input"
                        class="controls"
                        type="text"
                        placeholder="Search Box"
                        style="z-index: 0;
    position: absolute;
        top: 59px;"
                    />
                    <div class="col-lg-12">
                        <label for="">
                            @lang('map.address'): <input id="map-search" class="form-control controls" type="text" placeholder="Search Box" size="104"></label><br>
                    </div>
                    <div id="map" style="height: 400px; width: 100%;"></div>

                    <div id="map-canvas"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">@lang('form.done')</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="to_address_input" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabelto"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                        <input id="to_address_model" class="form-control controls" type="hidden">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-lg-12">
                        <label for="">
                            @lang('map.address'): <input id="maps-search" class="form-control controls" type="text" placeholder="Search Box" size="104"></label><br>
                    </div>
                    <div id="maps" style="height: 400px; width: 100%;"></div>

                    <div id="map-canvas"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">@lang('form.done')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

